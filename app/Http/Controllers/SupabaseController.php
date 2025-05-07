<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\SupabaseService;

class SupabaseController extends Controller
{
    protected SupabaseService $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function queryTopPrices(): JsonResponse
    {
        try {
            $data = $this->supabase->get('farma', [
                'select' => 'title,price_num',
                'order' => 'price_num.desc',
                'limit' => 10
            ]);
            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
