<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MemoryService;
use App\Services\LlmGatewayService;
use App\Services\ToolManifestService;
use Illuminate\Http\JsonResponse;

class ChatApiController extends Controller
{
    protected MemoryService $memory;
    protected LlmGatewayService $llmService;
    protected ToolManifestService $toolService;

    public function __construct(
        MemoryService $memory,
        LlmGatewayService $llmService,
        ToolManifestService $toolService
    ) {
        $this->memory = $memory;
        $this->llmService = $llmService;
        $this->toolService = $toolService;
    }

    /**
     * Handle chat message from API
     */
    public function send(Request $request): JsonResponse
    {
        // Валидация входных данных
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Генерируем уникальный ID на основе IP и User-Agent
        $ip = $request->ip() ?? '127.0.0.1';
        $userAgent = $request->header('User-Agent') ?? 'Unknown';
        $sessionId = md5($ip . $userAgent);
        
        $userMessage = $validated['message'];

        try {
            // Получаем контекст из памяти
            $context = $this->memory->getContext($sessionId, $userMessage);
            
            // Получаем ответ от LLM
            $tools = $this->toolService->getToolsManifest();
            $assistantResponse = $this->llmService->chat($context, $tools);
            
            // Сохраняем разговор в памяти
            $this->memory->rememberConversation($sessionId, $userMessage, $assistantResponse);
            
            return response()->json([
                'messages' => [
                    ['role' => 'assistant', 'content' => $assistantResponse]
                ]
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Chat API error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'session_id' => $sessionId,
                'message' => $userMessage
            ]);
            
            return response()->json([
                'error' => 'Произошла ошибка при обработке запроса',
                'messages' => [
                    ['role' => 'assistant', 'content' => 'Извините, произошла ошибка. Пожалуйста, попробуйте снова.']
                ]
            ]);
        }
    }
}
