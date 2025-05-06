<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class SupabaseController extends Controller
{
    public function testQuery(): JsonResponse
    {
        try {
            $url = rtrim(env('SUPABASE_API_URL'), '/') . '/farma';

            $response = Http::withHeaders([
                'apikey' => env('SUPABASE_API_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_API_KEY'),
            ])->get($url, [
                'select' => 'title,price,brand',
                'limit' => 3
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('Supabase error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Supabase request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ], $response->status());
        } catch (\Throwable $e) {
            Log::error('Supabase exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}