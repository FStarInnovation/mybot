<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\SiteScanController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SupabaseController;
use App\Http\Controllers\TestLlmUIController;
use App\Http\Controllers\LlmBridgeController;

// Маршрут для корневого пути
Route::get('/', function () {
    return 'MyBot is running! <a href="/llm/form">Go to LLM Interface</a>';
});

// Add a new route for admin dashboard
Route::get('/admin/dashboard', function () {
    return 'Admin Dashboard - <a href="/llm/form">Go to LLM Interface</a>';
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

Route::get('/llm/test', function () {
    $controller = new LlmBridgeController();
    return $controller->query(request()->merge([
        'prompt' => '¿Cuál es el ibuprofeno 400 mg más barato?',
        'embedding' => array_fill(0, 768, 0.01)
    ]));
});

Route::get('/llm/form', [TestLlmUIController::class, 'showForm']);
Route::post('/llm/form', [TestLlmUIController::class, 'handleForm']);
Route::post('/llm/query', [TestLlmUIController::class, 'handleForm']);