<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SupabaseService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('SUPABASE_URL'), '/') . '/rest/v1';
        $this->apiKey = env('SUPABASE_API_KEY');
    }

    public function get(string $table, array $filters = []): array
    {
        $response = Http::withHeaders([
            'apikey' => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get("{$this->baseUrl}/{$table}", $filters);

        if ($response->failed()) {
            throw new \Exception("Supabase error: " . $response->body());
        }

        return $response->json();
    }
}