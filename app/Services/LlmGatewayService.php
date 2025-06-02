<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LlmGatewayService
{
    /**
     * Отправить массив сообщений ([role,content]) в FastAPI шлюз и вернуть text ответа.
     * @param array<int,array{role:string,content:string}> $messages
     */
    public function chat(array $messages): string
    {
        $payload = [
            'model'    => 'llama3',
            'messages' => $messages,
            'stream'   => false,
        ];

        $resp = Http::timeout(60)->post(config('services.llm.endpoint'), $payload);

        if (!$resp->ok() || !isset($resp['choices'][0]['message']['content'])) {
            Log::error('LLM API error', [
                'status' => $resp->status(),
                'body'   => $resp->body(),
            ]);
            return 'Извините, я сейчас недоступен.';
        }

        // Clean up special template tokens that some models may return
        $clean = $resp['choices'][0]['message']['content'] ?? '';
        // remove <\|im_start\|> and <\|im_end\|> tokens and any surrounding whitespace
        $clean = preg_replace('/<\|im_start\|>|<\|im_end\|>/i', '', $clean);
        return trim($clean);
    }
}
