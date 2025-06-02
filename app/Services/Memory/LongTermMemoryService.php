<?php

namespace App\Services\Memory;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use App\Services\EmbeddingGatewayService;

class LongTermMemoryService
{
    protected float $similarityThreshold;
    protected int $maxResults;
    protected EmbeddingGatewayService $embeddingService;

    public function __construct(EmbeddingGatewayService $embeddingService)
    {
        $config = config('memory.long_term');
        $this->similarityThreshold = $config['similarity_threshold'];
        $this->maxResults = $config['max_results'];
        $this->embeddingService = $embeddingService;
    }

    public function searchSimilar(string $query, ?string $sessionId = null): Collection
    {
        try {
            // Получаем эмбеддинг для запроса
            $embedding = $this->embeddingService->getEmbedding($query);
            
            // Ищем похожие векторы в Neon
            $results = DB::select(
                "SELECT content, metadata, 
                       1 - (embedding <=> ?) as similarity
                FROM memories
                WHERE 1 - (embedding <=> ?) > ?
                ORDER BY similarity DESC
                LIMIT ?",
                [
                    $embedding,
                    $embedding,
                    $this->similarityThreshold,
                    $this->maxResults,
                ]
            );

            return collect($results)
                ->map(fn($row) => (array) $row);
                
        } catch (\Exception $e) {
            Log::error('Long-term memory search failed', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);
            return collect();
        }
    }

    public function saveMemory(
        string $content, 
        array $metadata = [],
        ?string $sessionId = null
    ): bool {
        try {
            $embedding = $this->embeddingService->getEmbedding($content);
            
            DB::insert(
                "INSERT INTO memories (content, embedding, metadata, session_id, created_at)
                VALUES (?, ?, ?::jsonb, ?, NOW())",
                [
                    $content,
                    $embedding,
                    json_encode($metadata),
                    $sessionId,
                ]
            );
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to save to long-term memory', [
                'content' => $content,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
