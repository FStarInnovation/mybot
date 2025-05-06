use Illuminate\Support\Facades\DB;

public function query(Request $request)
{
    $prompt = $request->input('prompt');
    $pharmacy = $request->input('pharmacy', 'Farmacity');

    // Получаем вектор эмбеддинга через API LLM
    $embeddingResponse = Http::timeout(20)->post(env('LLM_API_URL'), [
        'prompt' => $prompt,
        'temperature' => 0.7,
        'max_tokens' => 0,
        'stream' => false
    ]);

    $embedding = $embeddingResponse['embedding'] ?? null;

    if (!$embedding) {
        return response()->json(['error' => 'Embedding not returned from LLM.'], 400);
    }

    // Поиск по вектору
    $results = DB::select("
        SELECT *, 1 - (embedding <=> ?) AS similarity
        FROM match_documents('farma', ?, 5)
        ORDER BY similarity DESC
    ", [json_encode($embedding), 'embedding']);

    return response()->json([
        'prompt' => $prompt,
        'results' => $results
    ]);
}