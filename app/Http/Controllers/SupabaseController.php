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
        $data = $request->json()->all();
        $prompt = $data['prompt'] ?? null;
        $embedding = $data['embedding'] ?? null;

        if (!is_array($embedding) || count($embedding) !== 768) {
            return response()->json([
                'error' => true,
                'message' => 'Embedding vector must be an array of 768 floats.'
            ], 400);
        }

        try {
            $data = $this->supabase->post('/rpc/match_documents', [
                'query_embedding' => $embedding,
                'match_count' => 3,
                'table_name' => 'farma',
                'filter' => '',
            ]);

            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}