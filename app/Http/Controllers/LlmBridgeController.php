<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\SupabaseService;

class LlmBridgeController extends Controller
{
    public function query(Request $request)
    {
        \Log::info('LLM запрос пришел:', $request->all());

        $prompt = $request->input('prompt');
        $embedding = $request->input('embedding');

        if (!$prompt || !is_array($embedding)) {
            return response()->json(['error' => true, 'message' => 'Нужен prompt и массив embedding.'], 422);
        }

        if (count($embedding) !== 768) {
            return response()->json(['error' => true, 'message' => 'Embedding должен содержать ровно 768 чисел.'], 422);
        }

        if (!collect($embedding)->every(fn($v) => is_numeric($v))) {
            return response()->json(['error' => true, 'message' => 'Все элементы embedding должны быть числами.'], 422);
        }

        try {
            $supabase = app()->make(\App\Services\SupabaseService::class);

            $results = $supabase->rpc('match_documents', [
                'query_embedding' => $embedding,
                'match_count'     => 3
            ]);

            $markdown = collect($results)->map(function ($item, $i) {
                return sprintf("%d. [%s](%s) — $%s (similaridad: %.2f)",
                    $i + 1,
                    $item['title'],
                    $item['url'],
                    number_format($item['price_num'], 2, ',', '.'),
                    $item['similarity'] ?? 0
                );
            })->implode("\n");

            \DB::table('llm_logs')->insert([
                'prompt' => $prompt,
                'embedding' => json_encode($embedding),
                'result' => json_encode($results),
                'created_at' => now(),
            ]);

            $llmResponse = Http::withHeaders([
                'Authorization' => 'Bearer secret123',
            ])->post('http://farmabot:secret123@203.57.40.162:10051/completion', [
                'model' => 'farmabot',
                'prompt' => "Analiza estas 3 ofertas:\n\n$markdown\n\n¿Cuál es la mejor? Explica brevemente en español.",
                'temperature' => 0.4,
                'max_tokens' => 200,
            ]);

            $llmText = $llmResponse->json('response') ?? 'No hubo respuesta del LLM.';
        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Supabase RPC error: ' . $e->getMessage(),
            ], 500);
        }

        // Проверка: есть ли хоть одно совпадение
        if (isset($results) && collect($results)->every(fn($r) => ($r['similarity'] ?? 0) == 0)) {
            return response()->json([
                'message' => 'Совпадений не найдено.'
            ]);
        }

        return response()->json([
            'prompt' => $prompt,
            'markdown' => $markdown,
            'llm' => $llmText,
            'results' => $results
        ]);
    }
}