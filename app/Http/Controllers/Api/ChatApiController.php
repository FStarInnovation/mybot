<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatApiController extends Controller
{
    /**
     * Handle chat message from API
     */
    public function send(Request $request): JsonResponse
    {
        // Валидация входных данных
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        
        $userMessage = $validated['message'];
        
        // Логируем запрос
        \Illuminate\Support\Facades\Log::info('Chat API request received', [
            'message' => $userMessage,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        // Простая логика для ответов
        $response = '';
        if (stripos($userMessage, 'ibuprofeno') !== false || stripos($userMessage, 'ибупрофен') !== false) {
            $response = 'Ибупрофен - это нестероидный противовоспалительный препарат, который используется для снижения высокой температуры и облегчения боли. Он доступен в различных формах, включая таблетки, капсулы и сиропы.';
        } elseif (stripos($userMessage, 'привет') !== false || stripos($userMessage, 'здравствуй') !== false || stripos($userMessage, 'hola') !== false) {
            $response = 'Здравствуйте! Чем я могу вам помочь сегодня?';
        } else {
            $response = 'Я могу помочь вам найти информацию о лекарствах и ответить на вопросы о здоровье. Что вас интересует?';
        }
        
        return response()->json([
            'messages' => [
                ['role' => 'assistant', 'content' => $response]
            ]
        ]);
    }
}
