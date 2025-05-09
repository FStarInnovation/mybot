<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\SupabaseService;

class LlmBridgeController extends Controller
{
    public function query(Request $request)
    {
        $prompt = $request->input('prompt');
        $embedding = $request->input('embedding');

        if (!$prompt || !$embedding || !is_array($embedding)) {
            return response()->json(['error' => true, 'message' => 'Prompt и embedding обязательны.'], 422);
        }

        try {
            $supabase = app()->make(\App\Services\SupabaseService::class);

            $results = $supabase->rpc('match_documents', [
                'query_embedding' => $embedding,
                'match_count'     => 3
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Supabase RPC error: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'prompt'  => $prompt,
            'results' => $results
        ]);
    }
}