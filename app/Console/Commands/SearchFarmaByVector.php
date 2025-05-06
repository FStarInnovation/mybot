<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SearchFarmaByVector extends Command
{
    protected $signature = 'farma:search {prompt}';
    protected $description = 'Search for similar products using vector embeddings';

    public function handle()
    {
        $prompt = $this->argument('prompt');

        $this->info("Получение embedding для: \"$prompt\"...");

        $response = Http::timeout(20)->post(env('LLM_API_URL'), [
            'prompt' => $prompt,
            'temperature' => 0.1,
            'max_tokens' => 0,
            'stream' => false
        ]);

        if (!$response->ok() || !isset($response['embedding'])) {
            $this->error("Ошибка при получении embedding");
            return 1;
        }

        $embedding = $response['embedding'];

        $this->info("Поиск по вектору в Supabase...");

        $results = DB::select("
            SELECT *, 1 - (embedding <=> ?) AS similarity
            FROM match_documents('farma', ?, 5)
            ORDER BY similarity DESC
        ", [json_encode($embedding), 'embedding']);

        foreach ($results as $row) {
            $this->line("📦 {$row->title} — 💲{$row->price} — 🔗 {$row->product_url} (Sim: " . round($row->similarity, 3) . ")");
        }

        return 0;
    }
}