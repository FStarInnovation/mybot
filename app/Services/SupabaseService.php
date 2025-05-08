<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SupabaseService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('SUPABASE_URL'),
            'headers'  => [
                'apikey'       => env('SUPABASE_ANON_KEY'),
                'Authorization'=> 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'Content-Type' => 'application/json',
            ],
            'http_errors' => false,
            'timeout'     => 15,
        ]);
    }

    /**
     * Вызов RPC‑функции в Supabase.
     *
     * @param string $endpoint   например: '/rpc/match_documents'
     * @param array  $payload    уже готовый ассоц‑массив тела запроса
     */
    public function post(string $endpoint, array $payload): array
    {
        try {
            $resp = $this->client->post($endpoint, [
                'json' => $payload,
            ]);

            $json = json_decode($resp->getBody()->getContents(), true);

            if ($resp->getStatusCode() >= 400) {
                throw new \RuntimeException(json_encode($json, JSON_UNESCAPED_UNICODE));
            }

            return $json;
        } catch (GuzzleException|\Throwable $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}