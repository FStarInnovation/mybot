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
        $query = collect($filters)->flatMap(function ($v, $k) {
            if (is_array($v)) {
                return collect($v)->map(fn($item) => urlencode($k) . '=' . urlencode($item));
            }
            return [urlencode($k) . '=' . urlencode($v)];
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
}