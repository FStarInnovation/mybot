<?php

use Illuminate\Support\Facades\Route;

// API-маршруты
Route::prefix('api/v1')->group(function () {
    Route::post('/ask', [\App\Http\Controllers\AskController::class, 'ask']);
    Route::post('/search_products', [\App\Http\Controllers\AskController::class, 'searchProducts']);
    // остальные API-маршруты...
});

// SPA fallback - важно! Должен быть ПОСЛЕ всех других маршрутов
Route::get('/{path?}', function () {
    return file_get_contents(public_path('index.html'));
})->where('path', '.*');

// Health check для Laravel Cloud
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
