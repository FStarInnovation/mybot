<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LlmService
{
    public function complete(array $messages): string
    {
        $response = Http::post(config('services.llm.endpoint'), [
            'model' => 'mistral',
            'messages' => $messages,
            'stream' => false,
        ]);

        return $response->json('choices.0.message.content');
    }
}