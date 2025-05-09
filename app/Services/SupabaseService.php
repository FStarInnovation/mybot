<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Лёгкий обёртка‑клиент для вызова REST/RPC‑эндпоинтов Supabase.
 *
 * • Использует *service‑role* key — даёт полный доступ к функциям (`rpc/*`)
 * • Гарантирует корректную склейку `base_uri` + относительного пути
 * • Бросает осмысленное исключение при HTTP ошибках или кривом JSON
 */
class SupabaseService
{
    protected Client $client;

    public function __construct(?Client $client = null)
    {
        // Позволяем передать mock‑клиент при тестировании
        $this->client = $client ?: new Client([
            // https://<project>.supabase.co/
            'base_uri'   => rtrim(env('SUPABASE_URL'), '/') . '/rest/v1/',
            'headers'    => [
                'apikey'        => env('SUPABASE_SERVICE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'Content-Type'  => 'application/json',
            ],
            'http_errors' => false, // обрабатываем сами
            'timeout'     => 15,
        ]);
    }

    /**
     * Вызов произвольного RPC‑метода Supabase.
     *
     * @param  string $functionName  без префикса `/rpc/`  (напр. `match_documents`)
     * @param  array  $args          тело запроса —  ассоц‑массив аргументов функции
     * @return array                 декодированный JSON‑ответ
     *
     * @throws \RuntimeException при любом ненулевом HTTP‑статусе или сбое сети/JSON
     */
    public function rpc(string $functionName, array $args = []): array
    {
        return $this->request("rpc/{$functionName}", $args);
    }

    /**
     * Низкоуровневый POST‑запрос на относительный путь Supabase REST API.
     *
     * @param  string $endpoint  напр. `rpc/match_documents`
     * @param  array  $payload   JSON‑данные
     */
    public function request(string $endpoint, array $payload): array
    {
        // ВАЖНО: убираем ведущий «/», иначе Guzzle отбросит base_uri‑path
        $endpoint = ltrim($endpoint, '/');

        try {
            $resp = $this->client->post($endpoint, ['json' => $payload]);
        } catch (GuzzleException $e) {
            throw new \RuntimeException("Network error: {$e->getMessage()}", 0, $e);
        }

        // Попытка прочитать тело
        $body = (string) $resp->getBody();
        $json = json_decode($body, true);

        if ($resp->getStatusCode() >= 300) {
            $msg = $json ?: $body ?: 'Unknown error';
            throw new \RuntimeException(
                sprintf('Supabase HTTP %d: %s', $resp->getStatusCode(), is_string($msg) ? $msg : json_encode($msg, JSON_UNESCAPED_UNICODE))
            );
        }

        if ($json === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON returned from Supabase: ' . json_last_error_msg());
        }

        return $json;
    }
    /**
     * Backward-compatibility alias for POST requests.
     *
     * @param string $endpoint
     * @param array $payload
     * @return array
     */
    public function post(string $endpoint, array $payload): array
    {
        return $this->request(ltrim($endpoint, '/'), $payload);
    }
}