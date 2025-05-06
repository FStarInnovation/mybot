<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller;

class SupabaseController extends Controller
{
    public function testQuery()
    {
        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_API_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_API_KEY'),
        ])->get(env('SUPABASE_API_URL') . '/farma', [
            'select' => 'title,price,brand',
            'limit' => 3
        ]);

        return $response->successful()
            ? response()->json($response->json())
            : response()->json(['error' => $response->body()], $response->status());
    }
}