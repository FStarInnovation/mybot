<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MemoryService;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    protected MemoryService $memory;

    public function __construct(MemoryService $memory)
    {
        $this->memory = $memory;
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
        $userMessage = $validated['message'];

        try {
            // Get context from both short and long-term memory
            $context = $this->memory->getContext($sessionId, $userMessage);
            
            // Get LLM response
            $llmService = app(\App\Services\LlmGatewayService::class);
            $assistantResponse = $llmService->chat($context);
            
            // Remember the conversation
            $this->memory->rememberConversation($sessionId, $userMessage, $assistantResponse);
            
            return response()->json([
                'messages' => [
                    ['role' => 'assistant', 'content' => $assistantResponse]
                ]
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Chat error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Произошла ошибка при обработке запроса',
                'messages' => [
                    ['role' => 'assistant', 'content' => 'Извините, произошла ошибка. Пожалуйста, попробуйте снова.']
                ]
            ]);
        }
    }

    /**
     * GET /api/chat/history
     * Return full chat history for current session.
     */
    public function history(Request $request): JsonResponse
    {
        $sessionId = $request->session()->getId();
        $history = $this->memory->getRecentMessages($sessionId);

        return response()->json([
            'messages' => $history->map(fn($msg) => [
                'role' => $msg['role'],
                'content' => $msg['content']
            ])->toArray()
        ]);
    }
}
