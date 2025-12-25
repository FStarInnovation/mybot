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
use Illuminate\Support\Facades\Redis;
use App\Models\SearchQuery;

// Health‑check endpoint for load balancer / platform probes
Route::get('/healthz', fn () => response()->json(['status' => 'ok']));

// Static SPA entry point
Route::get('/', function () {
    return response()->file(public_path('build/index.html'), [
        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ]);
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
    Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
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

// Explicit routes for Service Worker and Web Manifest when served from Laravel dev server
Route::get('/service-worker.js', function () {
    return response()->file(public_path('build/service-worker.js'), [
        'Content-Type' => 'text/javascript',
        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ]);
});

Route::get('/manifest.json', function () {
    return response()->file(public_path('manifest.json'), [
        'Content-Type' => 'application/manifest+json',
        'Cache-Control' => 'public, max-age=2592000',
    ]);
});

// Serve _app immutable assets root path by mapping to build/_app with correct MIME types
Route::get('_app/{path}', function ($path) {
    $filePath = public_path("build/_app/{$path}");

    if (!file_exists($filePath)) {
        abort(404);
    }

    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $mimeTypes = [
        'js'   => 'text/javascript',
        'css'  => 'text/css',
        'json' => 'application/json',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'svg'  => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
    ];

    $headers = [];
    if (isset($mimeTypes[$extension])) {
        $headers['Content-Type'] = $mimeTypes[$extension];
    }

    // Cache busting filenames already include a hash, so we can cache for a month
    $headers['Cache-Control'] = 'public, max-age=2592000';

    return response()->file($filePath, $headers);
})->where('path', '.*');

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

// Диагностический маршрут для проверки доступности /chat
Route::get('/chat-test', function() {
    return 'Chat route is working through Laravel - ' . date('Y-m-d H:i:s');
});

// Diagnostic route to verify Redis (short-term) and PostgreSQL (long-term) operations
Route::get('/memory-test', function () {
    // Short-term memory (Upstash Redis)
    $timestamp = now()->toDateTimeString();
    Redis::set('memory_test', $timestamp);
    $redisValue = Redis::get('memory_test');

    // Long-term memory (Neon PostgreSQL)
    $query = SearchQuery::create([
        'query' => 'memory_test ' . $timestamp,
        'results_count' => random_int(0, 10),
    ]);

    return response()->json([
        'redis_value' => $redisValue,
        'search_query_saved' => $query->only(['id', 'query', 'created_at']),
    ]);
});

// SPA маршрут для SvelteKit приложения
// Должен быть в самом конце файла, чтобы не перехватывать другие маршруты
Route::get('/{path?}', function () {
    return response()->file(public_path('build/index.html'), [
        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ]);
})->where('path', '^(?!api).*$')->name('spa');

// Catch-all SPA route for SvelteKit app
Route::fallback(function () {
    return response()->file(public_path('build/index.html'), [
        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ]);
});

// // 1) Любой GET-запрос на /chat или /chat/... возвращает ваш Blade-шаблон
// Route::view('/chat/{any?}', 'app')
//      ->where('any', '.*'); // Закомментировано, т.к. /chat должен обслуживаться SPA (index.html)