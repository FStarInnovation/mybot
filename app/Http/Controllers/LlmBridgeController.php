<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class LlmBridgeController extends Controller
{
    public function query(Request $request)
    {
        $prompt = $request->input('prompt');
        $pharmacy = $request->input('pharmacy', 'Farmacity');

        if (!$prompt) {
            return response()->json(['error' => 'Prompt is required.'], 422);
        }

        // Получение embedding-вектора от LLM
        $llmResponse = Http::timeout(20)->post(env('LLM_API_URL'), [
            'model' => env('LLM_API_MODEL', 'mistral'),
            'prompt' => $prompt,
            'temperature' => 0.7,
            'max_tokens' => 100
        ]);

        if (!$llmResponse->ok()) {
            return response()->json(['error' => 'LLM API request failed.'], 502);
        }

        $embedding = $llmResponse->json('embedding');

        if (!$embedding || !is_array($embedding)) {
            return response()->json(['error' => 'Invalid embedding response.'], 400);
        }

        // Выполнение поиска по вектору с помощью Supabase встроенной функции
        try {
            $results = DB::select("
                SELECT id, content, metadata, 1 - (embedding <=> ?) AS similarity
                FROM match_documents('farma', 'embedding', 5)
                ORDER BY similarity DESC
            ", [json_encode($embedding)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Supabase vector search failed.', 'details' => $e->getMessage()], 500);
        }

        return response()->json([
            'prompt' => $prompt,
            'results' => $results
        ]);
    }
}