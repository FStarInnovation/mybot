<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LLMController;
use App\Services\SupabaseService;

Route::post('/llm', [LLMController::class, 'handle']);

Route::get('/supabase-test', function () {
    $service = app(SupabaseService::class);

    try {
        $data = $service->get('farma', [
            'select' => 'title,price_num',
            'order' => 'price_num.desc',
            'limit' => 5
        ]);

        return response()->json($data);
    } catch (\Throwable $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 500);
    }
});