<?php

use Illuminate\Support\Facades\Route;

// API-маршруты
Route::prefix('api/v1')->group(function () {
    Route::post('/ask', [\App\Http\Controllers\AskController::class, 'ask']);
    Route::post('/search_products', [\App\Http\Controllers\AskController::class, 'searchProducts']);
    // остальные API-маршруты...
});

// Health check для Laravel Cloud - должен быть ПЕРЕД catch-all
Route::get('/health', function () {
    return response()->json([
        'status'     => 'ok',
        'timestamp'  => now()->toIso8601String(),
        'framework'  => app()->version(),
    ], 200);
});

// Дополнительный health check для Laravel Cloud (который ищет health.php)
Route::get('/health.php', function () {
    return response()->json([
        'status'     => 'ok',
        'timestamp'  => now()->toIso8601String(),
        'framework'  => app()->version(),
    ], 200);
});

// Явные маршруты для SPA-подмаршрутов
Route::get('/chat', function () {
    return file_get_contents(public_path('index.html'));
});

Route::get('/chat/{path?}', function () {
    return file_get_contents(public_path('index.html'));
})->where('path', '.*');

// SPA fallback - важно! Должен быть ПОСЛЕ всех других маршрутов
Route::get('/{path?}', function () {
    return file_get_contents(public_path('index.html'));
})->where('path', '.*');
