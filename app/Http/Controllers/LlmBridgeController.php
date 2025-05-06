use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

public function query(Request $request)
{
    $prompt = $request->input('prompt');
    $pharmacy = $request->input('pharmacy', 'Farmacity');

    // Получаем эмбеддинг из LLM
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

    // Поиск похожих товаров по embedding
    $results = DB::select("
        SELECT *
        FROM match_documents('farma', 'embedding', 5, ?)
        ORDER BY similarity DESC
    ", [json_encode($embedding)]);

    return response()->json([
        'prompt' => $prompt,
        'results' => $results
    ]);
}