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

// Health‑check endpoint for load balancer / platform probes
Route::get('/healthz', fn () => response()->json(['status' => 'ok']));

// Static SPA entry point
Route::get('/', function () {
    return response()->file(public_path('build/index.html'));
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

// Ключевые маршруты для SvelteKit ассетов
Route::get('build/manifest.webmanifest', function () {
    return response()->file(public_path('build/manifest.webmanifest'), [
        'Content-Type' => 'application/manifest+json',
        'Cache-Control' => 'public, max-age=2592000',
    ]);
});

Route::get('build/registerSW.js', function () {
    return response()->file(public_path('build/registerSW.js'), [
        'Content-Type' => 'text/javascript',
        'Cache-Control' => 'public, max-age=86400',
    ]);
});

Route::get('build/sw.js', function () {
    return response()->file(public_path('build/sw.js'), [
        'Content-Type' => 'text/javascript',
        'Cache-Control' => 'public, max-age=86400',
    ]);
});

// Общие маршруты для JavaScript и CSS файлов
Route::get('build/_app/immutable/entry/{file}.js', function ($file) {
    return response()->file(public_path("build/_app/immutable/entry/{$file}.js"), [
        'Content-Type' => 'text/javascript',
        'Cache-Control' => 'public, max-age=2592000',
    ]);
});

Route::get('build/_app/immutable/chunks/{file}.js', function ($file) {
    return response()->file(public_path("build/_app/immutable/chunks/{$file}.js"), [
        'Content-Type' => 'text/javascript',
        'Cache-Control' => 'public, max-age=2592000',
    ]);
});

Route::get('build/_app/immutable/assets/{file}.css', function ($file) {
    return response()->file(public_path("build/_app/immutable/assets/{$file}.css"), [
        'Content-Type' => 'text/css',
        'Cache-Control' => 'public, max-age=2592000',
    ]);
});

// Остальные ассеты обслуживаются напрямую или через StaticFilesMiddleware

// Явные маршруты для /chat, обрабатываемые контроллером
Route::get('/chat', [App\Http\Controllers\SpaController::class, 'serve']);
Route::get('/chat/{any}', [App\Http\Controllers\SpaController::class, 'serve'])->where('any', '.*');

// SPA fallback for client-side routing
Route::get('/{path?}', function () {
    return response()->file(public_path('build/index.html'));
})->where('path', '(?!chat).*')->name('spa');

// Fallback to static index.html
Route::fallback(function () {
    return response()->file(public_path('build/index.html'));
});

// // 1) Любой GET-запрос на /chat или /chat/... возвращает ваш Blade-шаблон
// Route::view('/chat/{any?}', 'app')
//      ->where('any', '.*'); // Закомментировано, т.к. /chat должен обслуживаться SPA (index.html)