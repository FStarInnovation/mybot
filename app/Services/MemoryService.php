<?php

namespace App\Services;

use App\Services\Memory\ShortTermMemoryService;
use App\Services\Memory\LongTermMemoryService;
use Illuminate\Support\Collection;

class MemoryService
{
    public function __construct(
        protected ShortTermMemoryService $shortTerm,
        protected LongTermMemoryService $longTerm
    ) {}

    public function getContext(string $sessionId, string $userMessage): array
    {
        // 1. Get recent conversation history
        $recentMessages = $this->shortTerm->getRecentMessages($sessionId);
        
        // 2. Search long-term memory if needed
        $longTermContext = $this->getRelevantLongTermMemory($userMessage, $sessionId);
        
        // 3. Format system message with context
        $systemMessage = $this->formatSystemMessage($longTermContext);
        
        // 4. Combine all messages and filter out null values
        $messages = array_merge(
            [['role' => 'system', 'content' => $systemMessage]],
            $recentMessages->toArray(),
            [['role' => 'user', 'content' => $userMessage]]
        );
        
        return array_filter($messages, fn($msg) => $msg !== null && isset($msg['role']) && isset($msg['content']));
    }
    
    public function getRecentMessages(string $sessionId, ?int $limit = null): Collection
    {
        return $this->shortTerm->getRecentMessages($sessionId, $limit);
    }
    
    public function rememberConversation(
        string $sessionId, 
        string $userMessage,
        string $assistantResponse
    ): void {
        // Save to short-term memory
        $this->shortTerm->addMessage($sessionId, 'user', $userMessage);
        $this->shortTerm->addMessage($sessionId, 'assistant', $assistantResponse);
        
        // Optionally save to long-term memory if the conversation is important
        if ($this->isWorthRemembering($userMessage, $assistantResponse)) {
            $this->longTerm->saveMemory(
                $assistantResponse,
                ['user_message' => $userMessage],
                $sessionId
            );
        }
    }
    
    protected function getRelevantLongTermMemory(string $query, ?string $sessionId = null): Collection
    {
        // Skip if the query is too short
        if (str_word_count($query) < 3) {
            return collect();
        }
        
        return $this->longTerm->searchSimilar($query, $sessionId);
    }
    
    protected function formatSystemMessage(Collection $longTermContext): string
    {
        $basePrompt = "Ты дружелюбный русскоязычный ассистент. Отвечай кратко и по существу.";
        
        if ($longTermContext->isEmpty()) {
            return $basePrompt;
        }
        
        $context = $longTermContext->map(
            fn($item) => "- {$item['content']}"
        )->implode("\n");
        
        return "$basePrompt\n\nКонтекст из предыдущих обсуждений:\n$context";
    }
    
    protected function isWorthRemembering(string $question, string $answer): bool
    {
        // Simple heuristic: remember if the answer is factual and not too short
        $minAnswerLength = 30;
        $isFactual = !preg_match('/не знаю|извините/i', $answer);
        
        return $isFactual && mb_strlen($answer) > $minAnswerLength;
    }
}
