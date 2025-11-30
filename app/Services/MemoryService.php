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

        // 2. Combine only history and the current user message.
        //    System prompts / instructions are now handled entirely on the LLM backend (RunPod).
        return array_merge(
            $recentMessages->toArray(),
            [['role' => 'user', 'content' => $userMessage]]
        );
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
        // System prompts are no longer injected on the Laravel side.
        // We keep this method for potential future use, but it returns only
        // a plain concatenation of long-term context (without any extra
        // role/instruction text).

        if ($longTermContext->isEmpty()) {
            return '';
        }

        return $longTermContext->map(
            fn($item) => "- {$item['content']}"
        )->implode("\n");
    }
    
    protected function isWorthRemembering(string $question, string $answer): bool
    {
        // Simple heuristic: remember if the answer is factual and not too short
        $minAnswerLength = 30;
        $isFactual = !preg_match('/не знаю|извините/i', $answer);
        
        return $isFactual && mb_strlen($answer) > $minAnswerLength;
    }
}
