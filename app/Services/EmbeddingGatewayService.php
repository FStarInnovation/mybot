<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Сервис-обёртка для работы с FastAPI энд-пойнтом /embedding.
 * Позволяет централизованно задавать таймаут, логировать ошибки
 * и валидировать структуру ответа.
 */
class EmbeddingGatewayService
{
    /**
     * Получить embedding для строки или списка строк.
     *
     * @param  string|array<int,string>  $input
     * @return array<int,float>  Вектор размерности 768
     */
    /**
     * Alias for embed() for backward compatibility
     * 
     * @param string $content
     * @return array
     */
    public function getEmbedding(string $content): array
    {
        return $this->embed($content);
    }

    /**
     * Get embedding for a string or list of strings.
     *
     * @param  string|array<int,string>  $input
     * @return array<int,float>  Vector of dimension 768
     */
    public function embed(array|string $input): array
    {
        $payload = [
            'input' => is_array($input) ? $input : [$input],
        ];

        $response = Http::timeout(40)
            ->post(config('services.embeddings.endpoint'), $payload);

        if (!$response->ok() || !isset($response['data'][0]['embedding'])) {
            Log::error('Embedding API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            throw new \RuntimeException('Embedding API error');
        }

        return $response['data'][0]['embedding'];
    }
}
