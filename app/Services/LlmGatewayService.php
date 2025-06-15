<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class LlmGatewayService
{
    private $http;
    private $tools;

    public function __construct()
    {
        $gateway = Config::get('llm.gateway');
        $retryCodes = Config::get('llm.retry_codes', [502, 503, 504]);
        $this->http = Http::timeout($gateway['timeout'])
            ->retry(
                $gateway['retry_attempts'],
                $gateway['retry_delay'],
                function ($exception, $request) use ($retryCodes) {
                    // Retry on connection exceptions or specific HTTP codes
                    if ($exception instanceof \Illuminate\Http\Client\ConnectionException) {
                        return true;
                    }

                    if (property_exists($exception, 'response') && $exception->response) {
                        $status = $exception->response->status();
                        return in_array($status, $retryCodes, true);
                    }

                    return false;
                }
            );
    }

    /**
     * Отправить сообщения в LLM и вернуть ответ.
     * @param array<int,array{role:string,content:string}> $messages
     * @param array $tools Манифест инструментов
     * @return string Ответ ассистента
     */
    public function chat(array $messages, array $tools = []): string
    {
        // Ensure system prompt is the first message
        $hasSystem = false;
        foreach ($messages as $m) {
            if (($m['role'] ?? '') === 'system') {
                $hasSystem = true;
                break;
            }
        }
        if (!$hasSystem) {
            array_unshift($messages, [
                'role'    => 'system',
                'content' => Config::get('llm.system_prompt'),
            ]);
        }


        $payload = [
            'service'  => 'search',
            'messages' => $messages,
            'stream'   => true,
        ];

        Log::debug('LLM payload', ['payload' => $payload]);
        try {
            $httpResponse = $this->http->withHeaders([
                    'Accept' => 'text/event-stream',
                ])->post(
                rtrim(Config::get('llm.gateway.url'), '/') . '/api/v1/ask',
                $payload
            );

            if ($httpResponse->failed()) {
                throw new \RuntimeException(
                    'LLM gateway returned HTTP ' . $httpResponse->status() . ' : ' . $httpResponse->body()
                );
            }

            // SSE body may contain multiple lines starting with "data: "
            $body  = (string) $httpResponse->body();
            $lines = preg_split('/\r?\n/', $body);
            $jsonStr = null;
            foreach ($lines as $line) {
                if (!str_starts_with($line, 'data:')) {
                    continue;
                }
                $candidate = trim(substr($line, 5));
                // Игнорируем маркер завершения
                if ($candidate === '[DONE]' || $candidate === '') {
                    continue;
                }
                $jsonStr = $candidate;
                break; // берём первый валидный JSON chunk
            }
            if (!$jsonStr) {
                throw new \RuntimeException('SSE response missing JSON chunk');
            }
            $response = json_decode($jsonStr, true);
            Log::debug('LLM raw response', ['response' => $response]);

            if (!is_array($response) || !isset($response['choices'][0])) {
                throw new \RuntimeException('Invalid response format from LLM gateway: ' . json_encode($response));
            }

            $message = $response['choices'][0]['message'];
            
            // Handle tool call
            if (isset($message['tool_call']) || isset($message['function_call']) || isset($message['tool_calls'])) {
                $func = $message['tool_call'] ?? $message['function_call'] ?? ($message['tool_calls'][0] ?? null);
                if ($func === null) {
                    return $this->cleanResponse($message['content'] ?? '');
                }

                $inner = $func['function'] ?? $func; // поддержка нового и старого форматов
                $name  = $inner['name'] ?? null;
                $args  = isset($inner['arguments'])
                    ? json_decode($inner['arguments'], true)
                    : [];

                if (!$name) {
                    throw new \RuntimeException('LLM tool call missing name');
                }

                // Call MCP tool
                $toolResponse = $this->callTool($name, $args);

                // Second LLM call with tool results
                $messages[] = ['role' => 'assistant', 'content' => $message['content'] ?? ''];
                $messages[] = ['role' => 'tool', 'name' => $name, 'content' => json_encode($toolResponse, JSON_UNESCAPED_UNICODE)];

                $payload2 = $payload;
                unset($payload2['tool_choice']);
                $payload2['messages'] = $messages;

                $secondHttp = $this->http->post(
                    Config::get('llm.gateway.url') . '/chat',
                    $payload2
                );

                if ($secondHttp->failed()) {
                    throw new \RuntimeException('LLM second call failed: ' . $secondHttp->status());
                }

                $secondResponse = $secondHttp->json();

                return $this->cleanResponse($secondResponse['choices'][0]['message']['content'] ?? '');
            }

            return $this->cleanResponse($message['content'] ?? '');
        } catch (\Exception $e) {
            Log::error('LLM Gateway error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
            return 'Извините, я сейчас недоступен.';
        }
    }

    /**
     * Вызвать инструмент через MCP
     */
    private function callTool(string $name, array $args)
    {
        $config = Config::get('llm.gateway');
        $toolUrl = rtrim($config['url'], '/') . "/tool/{$name}";

        $http = Http::timeout($config['timeout'] ?? 30);

        $user = config('services.mcp.auth_username');
        $pass = config('services.mcp.auth_password');
        if ($user && $pass) {
            $http = $http->withBasicAuth($user, $pass);
        }

        return $http->post($toolUrl, $args)->json();
    }

    /**
     * Очистить специальные токены из ответа
     */
    private function cleanResponse(string $content): string
    {
        return trim(preg_replace('/<\\|im_start\\>|<\\|im_end\\>/i', '', $content));
    }

    /**
     * Получить векторное представление текста
     */
    public function embeddings(array $input): array
    {
        return $this->http->post(
            Config::get('llm.gateway.url') . '/embedding',
            ['input' => $input, 'model' => 'embed']
        )->json();
    }
}
