<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LlmService
{
    public function complete(string $prompt): string
    {
        $response = Http::post(config('services.llm.endpoint'), [
            'model' => config('services.llm.model', 'mistral'),
            'prompt' => $prompt,
            'temperature' => (float) config('services.llm.temp', 0.7),
            'max_tokens' => (int) config('services.llm.max_tokens', 200),
        ]);

        return $response->json('content');
    }
}