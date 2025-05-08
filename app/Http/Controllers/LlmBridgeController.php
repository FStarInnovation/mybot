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

        $embedding = $request->input('embedding');
        if (!$embedding || !is_array($embedding)) {
            return response()->json(['error' => 'Embedding is required and must be an array.'], 422);
        }

        // pgvector expects a literal in the form '[0.1,0.2,...]'
        $vectorLiteral = '[' . implode(',', $embedding) . ']';

        // Выполнение поиска по вектору с помощью Supabase встроенной функции
        try {
            $results = DB::select(
                "SELECT id,
                        title,
                        price_num,
                        brand,
                        url,
                        1 - (embedding <-> :vec)::float AS similarity
                 FROM farma
                 WHERE (:pharmacy IS NULL OR pharmacy = :pharmacy)
                 ORDER BY embedding <-> :vec
                 LIMIT 5",
                [
                    'vec'      => $vectorLiteral,
                    'pharmacy' => $pharmacy === 'all' ? null : $pharmacy,
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Supabase vector search failed.', 'details' => $e->getMessage()], 500);
        }

        return response()->json([
            'prompt' => $prompt,
            'results' => $results
        ]);
    }
}