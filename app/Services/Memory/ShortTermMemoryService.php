<?php

namespace App\Services\Memory;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class ShortTermMemoryService
{
    protected string $prefix;
    protected int $ttl;
    protected int $maxMessages;

    public function __construct()
    {
        $config = config('memory.short_term');
        $this->prefix = $config['prefix'];
        $this->ttl = $config['ttl'];
        $this->maxMessages = $config['max_messages'];
    }

    public function getRecentMessages(string $sessionId, ?int $limit = 5): Collection
    {
        try {
            $key = $this->getKey($sessionId);
            $messages = Redis::lrange($key, 0, $limit ?? $this->maxMessages - 1);
            
            return collect($messages)
                ->map(fn($msg) => json_decode($msg, true))
                ->filter(fn($msg) => $msg !== null && isset($msg['role']) && isset($msg['content']));
        } catch (\Exception $e) {
            Log::error('Failed to fetch recent messages', [
                'session' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            return collect();
        }
    }

    public function addMessage(string $sessionId, string $role, string $content): void
    {
        try {
            $key = $this->getKey($sessionId);
            $message = json_encode([
                'role' => $role,
                'content' => $content,
                'timestamp' => now()->toIso8601String(),
            ]);

            Redis::pipeline(function ($pipe) use ($key, $message) {
                $pipe->lpush($key, $message);
                $pipe->ltrim($key, 0, $this->maxMessages - 1);
                $pipe->expire($key, $this->ttl);
            });
        } catch (\Exception $e) {
            Log::error('Failed to add message to short-term memory', [
                'session' => $sessionId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function clearSession(string $sessionId): void
    {
        Redis::del($this->getKey($sessionId));
    }

    protected function getKey(string $sessionId): string
    {
        return $this->prefix . $sessionId;
    }
}
