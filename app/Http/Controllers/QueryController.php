<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QueryController extends Controller
{
    public function handle(Request $request)
    {
        $query = $request->input('query');

        // 1. Получаем embedding от модели LLM
        $embeddingResponse = Http::post('http://farmabot:secret123@203.57.40.162:10051/embeddings', [
            'model' => 'farmabot',
            'input' => $query,
        ]);

        if (!$embeddingResponse->ok()) {
            return response()->json(['error' => 'Ошибка при получении эмбеддинга'], 500);
        }

        $embedding = $embeddingResponse->json()['data'][0]['embedding'];

        // 2. Запрос в Supabase
        $supabaseQuery = Http::withHeaders([
            'apikey' => env('SUPABASE_API_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_API_KEY'),
        ])->post(env('SUPABASE_URL') . '/rest/v1/rpc/match_documents', [
            'query_embedding' => $embedding,
            'match_count' => 5,
        ]);

        if (!$supabaseQuery->ok()) {
            return response()->json(['error' => 'Ошибка Supabase', 'details' => $supabaseQuery->body()], 500);
        }

        $results = $supabaseQuery->json();

        // 3. Отправка результата в LLM (для ответа)
        $llmResponse = Http::post('http://farmabot:secret123@203.57.40.162:10051/completion', [
            'model' => 'farmabot',
            'prompt' => "Вот результаты похожих товаров:\n\n" . json_encode($results, JSON_PRETTY_PRINT) . "\n\nСделай краткий вывод для пользователя на испанском, сравни цены и скажи какой товар выгоднее.",
            'stream' => false,
        ]);

        if (!$llmResponse->ok()) {
            return response()->json(['error' => 'Ошибка LLM'], 500);
        }

        return response()->json([
            'result' => $llmResponse->json()['response'],
            'raw' => $results,
        ]);
    }
}