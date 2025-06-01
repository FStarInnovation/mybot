<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatHistoryCache;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    protected ChatHistoryCache $history;

    public function __construct()
    {
        // Resolve with TTL from config (defaults to 86400)
        $this->history = new ChatHistoryCache((int) config('chat.history_ttl'));
    }

    /**
     * POST /api/chat/send
     * Store user message and return a stub assistant reply (placeholder until LLM integration).
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sessionId = $request->session()->getId();

        // Push user message
        $userMsg = [
            'role'    => 'user',
            'content' => $validated['message'],
            'ts'      => now()->toISOString(),
        ];
        $this->history->push($sessionId, $userMsg);

        // Process via ChatService (LLM placeholder)
        $assistantMessages = app(\App\Services\ChatService::class)
            ->process($sessionId, $validated['message']);

        return response()->json(['messages' => $assistantMessages]);
    }

    /**
     * GET /api/chat/history
     * Return full chat history for current session.
     */
    public function history(Request $request): JsonResponse
    {
        $sessionId = $request->session()->getId();
        return response()->json(['history' => $this->history->all($sessionId)]);
    }
}
