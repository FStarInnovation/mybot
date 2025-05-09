<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\SupabaseService;

class SupabaseController extends Controller
{
    protected SupabaseService $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function queryEmbedding(Request $request): JsonResponse
    {
        /** 1. Гарантированно декодируем JSON */
        $data = json_decode($request->getContent(), true);
        \Log::info('[LLM DEBUG] Входной JSON:', $data);

        /** 2. Проверяем обязательные поля */
        $prompt    = $data['prompt'] ?? null;
        $embedding = $data['embedding'] ?? $data['query_embedding'] ?? null;

        \Log::info('[LLM DEBUG] prompt: ' . $prompt);
        \Log::info('[LLM DEBUG] embedding count: ' . (is_array($embedding) ? count($embedding) : 'не массив'));

        if (! $prompt || ! is_array($embedding) || count($embedding) !== 768) {
            \Log::warning('[LLM DEBUG] Неверный формат запроса', [
                'prompt' => $prompt,
                'embedding_is_array' => is_array($embedding),
                'embedding_count' => is_array($embedding) ? count($embedding) : null
            ]);
            return response()->json([
                'error'   => true,
                'message' => 'Нужно передать "prompt" и массив "embedding" из 768 чисел',
            ], 400);
        }

        /** 3. Запрашиваем Supabase */
        try {
            $result = $this->supabase->rpc('match_documents', [
                'query_embedding' => $embedding,
                'match_count'     => 3,
            ]);

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json([
                'error'   => true,
                'message' => 'Supabase POST error: ' . $e->getMessage(),
            ], 500);
        }
    }
}