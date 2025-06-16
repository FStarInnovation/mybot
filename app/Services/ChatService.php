<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Services\LlmGatewayService;

class ChatService
{
    protected ChatHistoryCache $history;
    protected LlmGatewayService $llm;

    public function __construct(LlmGatewayService $llm)
    {
        $this->history = new ChatHistoryCache((int) config('chat.history_ttl'));
        $this->llm     = $llm;
    }

    /**
     * Отправить полный контекст в LLM и вернуть ответ.
     */
    public function process(string $sessionId, string $message): array
    {
        // Логируем запрос пользователя (fire-and-forget)
        try {
            \App\Models\SearchQuery::create([
                'query' => $message,
                'user_id' => null,
                'results_count' => null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to save search query', ['e' => $e->getMessage()]);
        }

        // Собираем историю для контекста
        $history   = $this->history->all($sessionId);
        $messages  = array_map(fn ($m) => [
            'role'    => $m['role'] ?? 'user',
            'content' => $m['content'] ?? '',
        ], $history);

        // Добавляем текущий запрос
        $messages[] = ['role' => 'user', 'content' => $message];

        // Запрашиваем LLM
        $assistantContent = $this->llm->chat($messages);

        // Формируем ответ и сохраняем в историю
        $assistantArr = [
            'role'    => 'assistant',
            'content' => $assistantContent,
            'ts'      => now()->toISOString(),
        ];

        $this->history->push($sessionId, $assistantArr);

        return [$assistantArr];
    }
}
