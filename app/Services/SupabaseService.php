<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SupabaseService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('SUPABASE_API_URL'), '/');
        $this->apiKey = env('SUPABASE_API_KEY');
    }

    public function get(string $table, array $filters = []): array
    {
        $query = collect($filters)->flatMap(function ($value, $key) {
            if (is_array($value)) {
                return collect($value)->map(fn($v) => [urlencode($key) => urlencode($v)]);
            }
            return [[urlencode($key) => urlencode($value)]];
        })->map(function ($pair) {
            $k = array_key_first($pair);
            $v = $pair[$k];
            return "{$k}={$v}";
        })->implode('&');

        $response = Http::withHeaders([
            'apikey' => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get("{$this->baseUrl}/{$table}?{$query}");

        if ($response->failed()) {
            throw new \Exception("Supabase error: " . $response->body());
        }

        return $response->json();
    }

    public function post(string $endpoint = 'match_documents', array $payload = []): array
    {
        $response = Http::withHeaders([
            'apikey' => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/rpc/{$endpoint}", [
            'filter' => '{}',
            'match_count' => 5,
            'query_embedding' => $payload['embedding'],
        ]);

        if ($response->failed()) {
            throw new \Exception("Supabase POST error: " . $response->body());
        }

        return $response->json();
    }
}