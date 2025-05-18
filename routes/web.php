<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\SiteScanController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SupabaseController;

// Маршрут для корневого пути
Route::get('/', function () {
    return view('welcome'); // Возвращает resources/views/welcome.blade.php
});

// Универсальные маршруты (web)
Route::post('/scan-sites', [SiteScanController::class, 'scan']);
Route::get('/results', [ResultsController::class, 'index']);
Route::post('/llm', [Controller::class, 'queryLLM']);

Route::get('/supabase', [SupabaseController::class, 'fetchTopExpensive']);

// Маршрут для очистки кеша
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return '✅ Laravel cache cleared!';
});

use Illuminate\Support\Facades\Http;

Route::get('/test-vector-match', function () {
    $embedding = include base_path('vector.php');

    $response = Http::withHeaders([
        'apikey' => env('SUPABASE_SERVICE_KEY'),
        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        'Content-Type' => 'application/json',
    ])->post(env('SUPABASE_URL') . '/rest/v1/rpc/match_documents', [
        'query_embedding' => $embedding,
        'match_threshold' => 0.75,
        'match_count' => 3,
        'filter' => null,
    ]);

    return $response->successful()
        ? $response->json()
        : ['error' => $response->status(), 'body' => $response->body()];
});

use App\Http\Controllers\LlmBridgeController;

Route::get('/llm/test', function () {
    $controller = new LlmBridgeController();
    return $controller->query(request()->merge([
        'prompt' => '¿Cuál es el ibuprofeno 400 mg más barato?',
        'embedding' => array_fill(0, 768, 0.01)
    ]));
});