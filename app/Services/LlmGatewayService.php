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
            'model'            => env('LLM_MODEL', 'llama'),
            'messages'         => $messages,
            'stream'           => false,
            'temperature'      => 0.5,
            'top_p'            => 0.9,
            'max_tokens'       => 512,
            'presence_penalty' => 0.0,
            'frequency_penalty'=> 0.0,
        ];

        $resp = Http::withHeaders([
            'Accept' => 'application/json',
        ])->timeout(120)->post(config('services.llm.endpoint'), $payload);

        if (!$resp->ok() || !isset($resp['choices'][0]['message']['content'])) {
            $errorDetails = [
                'status' => $resp->status(),
                'body' => $resp->body(),
                'endpoint' => config('services.llm.endpoint'),
                'request_payload' => $payload
            ];
            
            
            // Log the error for debugging
            Log::error('LLM API error', $errorDetails);
            
            // Return detailed error for debugging (temporary)
            return 'LLM API Error: ' . json_encode($errorDetails, JSON_UNESCAPED_UNICODE);
        }

        // Clean up special template tokens that some models may return
        $clean = $resp['choices'][0]['message']['content'] ?? '';
        // remove <\|im_start\|> and <\|im_end\|> tokens and any surrounding whitespace
        $clean = preg_replace('/<\|im_start\|>|<\|im_end\|>/i', '', $clean);
        return trim($clean);
    }
}
