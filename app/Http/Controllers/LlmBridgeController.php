<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\SupabaseService;

class LlmBridgeController extends Controller
{
    public function query(Request $request, SupabaseService $supabase)
    {
        $prompt = $request->input('prompt');

        if (!$prompt) {
            return response()->json(['error' => 'Prompt is required.'], 422);
        }

        $embedding = $request->input('embedding');
        if (!$embedding || !is_array($embedding)) {
            return response()->json(['error' => 'Embedding is required and must be an array.'], 422);
        }

        try {
            $results = $supabase->callRpc('match_documents', [
                'query_embedding' => $embedding,
                'match_count'     => 5,
                'filter'          => null,   // можно передать JSON‑фильтр, если понадобится
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error'   => true,
                'message' => 'Supabase RPC failed: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'prompt' => $prompt,
            'results' => $results
        ]);
    }
}