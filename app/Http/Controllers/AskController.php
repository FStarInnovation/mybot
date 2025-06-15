<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\ChatService;
use Illuminate\Support\Facades\Log;

class AskController extends Controller
{
    public function ask(Request $request, ChatService $chat): StreamedResponse
    {
        $service  = $request->input('service', 'search');
        $messages = $request->input('messages', []);

        if (empty($messages) || !is_array($messages)) {
            abort(422, 'messages array is required');
        }

        // For now we only support the first user message
        $userContent = $messages[0]['content'] ?? '';
        if (!$userContent) {
            abort(422, 'First message content is empty');
        }

        $sessionId = $request->input('session_id', 'ask-' . uniqid());

        return response()->stream(function () use ($chat, $sessionId, $userContent) {
            try {
                $result = $chat->process($sessionId, $userContent);
                $data   = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                echo "data: {$data}\n\n";
            } catch (\Throwable $e) {
                Log::error('AskController error', ['error' => $e->getMessage()]);
                $err = json_encode(['error' => $e->getMessage()]);
                echo "data: {$err}\n\n";
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }
}
