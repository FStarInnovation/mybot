<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

/**
 * Lightweight wrapper around Redis list operations for storing
 * per-session chat history. Works seamlessly with Upstash Redis
 * (uses standard TLS Redis protocol).
 */
class ChatHistoryCache
{
    /** Prefix for Redis keys. */
    protected string $prefix = 'chat:history:';

    /** Default TTL (seconds) for a chat history bucket. */
    protected int $ttl;

    public function __construct(int $ttl = 3600)
    {
        $this->ttl = $ttl;
    }

    /**
     * Append a message to the user session list.
     *
     * @param string $sessionId Typically a browser session / auth user id
     * @param array  $message   Plain array with at least ['role'=>'user|assistant','content'=>string]
     */
    public function push(string $sessionId, array $message): void
    {
        try {
            $key = $this->key($sessionId);
            Redis::rpush($key, json_encode($message));
            // refresh TTL
            Redis::expire($key, $this->ttl);
        } catch (\Throwable $e) {
            Log::warning('Redis push failed', ['msg' => $e->getMessage()]);
        }
    }

    /**
     * Retrieve full chat history for a session (oldest ‑> newest).
     * Returns empty array if no history.
     */
    public function all(string $sessionId): array
    {
        try {
            $key   = $this->key($sessionId);
            $items = Redis::lrange($key, 0, -1);
            return array_map(fn ($raw) => json_decode($raw, true), $items);
        } catch (\Throwable $e) {
            Log::warning('Redis read failed', ['msg' => $e->getMessage()]);
            return [];
        }
    }

    /** Remove chat history completely. */
    public function clear(string $sessionId): void
    {
        try {
            Redis::del($this->key($sessionId));
        } catch (\Throwable $e) {
            Log::warning('Redis clear failed', ['msg' => $e->getMessage()]);
        }
    }

    private function key(string $sessionId): string
    {
        return $this->prefix . $sessionId;
    }
}
