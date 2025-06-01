<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChatService
{
    protected ChatHistoryCache $history;

    public function __construct(ChatHistoryCache $history)
    {
        $this->history = $history;
    }

    /**
     * Basic synchronous processing without LLM (placeholder).
     */
    public function process(string $sessionId, string $message): array
    {
        // Save user query to DB (SearchQuery model) asynchronously (fire and forget)
        try {
            \App\Models\SearchQuery::create([
                'query' => $message,
                'user_id' => null,
                'results_count' => null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to save search query', ['e' => $e->getMessage()]);
        }

        // Placeholder assistant reply (echo)
        $assistantMsg = 'Echo: ' . $message;

        // Push to history
        $this->history->push($sessionId, [
            'role' => 'assistant',
            'content' => $assistantMsg,
            'ts' => now()->toISOString(),
        ]);

        return [$assistantMsg];
    }
}
