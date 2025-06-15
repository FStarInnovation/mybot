<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Services\LlmGatewayService;
use App\Services\ToolManifestService;

class ChatService
{
    protected ChatHistoryCache $history;
    protected LlmGatewayService $llm;
    /**
     * @var array<int, array<string,mixed>>
     */
    protected array $tools = [];

    public function __construct(LlmGatewayService $llm, ToolManifestService $manifest)
    {
        $this->history = new ChatHistoryCache((int) config('chat.history_ttl'));
        $this->llm     = $llm;
        $this->tools  = $manifest->getToolsManifest();
    }

    /**
     * Отправить полный контекст в LLM и вернуть ответ.
     */
    public function process(string $sessionId, string $message): array
    {
        // Логируем запрос пользователя (fire-and-forget)
        try {
            \App\Models\SearchQuery::create([
                'query' => $message,
                'user_id' => null,
                'results_count' => null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to save search query', ['e' => $e->getMessage()]);
        }

        // Определяем, нужен ли поиск цен
        $lower = mb_strtolower($message, 'UTF-8');
        $priceKeywords = ['precio', 'cuánto', 'cuanto', 'costo', 'vale', 'comprar', '$'];
        $needsSearch = false;
        foreach ($priceKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                $needsSearch = true;
                break;
            }
        }

        // Выбираем подходящий системный промпт
        $systemPrompt = $needsSearch ? config('llm.system_prompt') : config('llm.generic_prompt');

        // Собираем историю для контекста
        $history   = $this->history->all($sessionId);
        $messages  = array_map(fn ($m) => [
            'role'    => $m['role'] ?? 'user',
            'content' => $m['content'] ?? '',
        ], $history);

        // Добавляем текущий запрос
        $messages[] = ['role' => 'user', 'content' => $message];

        // Запрашиваем LLM
        // Отправляем инструмент поиска только если запрос явно о цене/compra
        $lower = mb_strtolower($message, 'UTF-8');
        $priceKeywords = ['precio', 'cuánto', 'cuanto', 'costo', 'vale', 'comprar', '$'];
        $needsSearch = false;
        foreach ($priceKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                $needsSearch = true;
                break;
            }
        }
        $toolsToSend = [];
        if ($needsSearch) {
            $toolsToSend = array_values(array_filter($this->tools, function ($t) {
                return ($t['function']['name'] ?? '') === 'search_products';
            }));
        }

        $assistantContent = $this->llm->chat($messages, $toolsToSend);

        // Формируем ответ и сохраняем в историю
        $assistantArr = [
            'role'    => 'assistant',
            'content' => $assistantContent,
            'ts'      => now()->toISOString(),
        ];

        $this->history->push($sessionId, $assistantArr);

        return [$assistantArr];
    }
}
