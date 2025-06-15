<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteScanController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\Controller; // LLM Bridge
use App\Http\Controllers\SupabaseController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\LlmBridgeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PushController;
use Illuminate\Http\Request;
use App\Services\ChatService;
use App\Services\LlmGatewayService;

Route::post('/scan-sites', [SiteScanController::class, 'scan']);
Route::get('/results', [ResultsController::class, 'index']);

Route::post('/llm', [Controller::class, 'queryLLM']);
Route::post('/llm/query', [SupabaseController::class, 'queryEmbedding']); // ← 
Route::post('/supabase-test', [SupabaseController::class, 'testQuery']);
Route::get('/supabase-test', [SupabaseController::class, 'testQuery']);

Route::post('/query', [LlmBridgeController::class, 'query'])->name('api.query');
Route::get('/ping', fn() => response()->json(['pong' => true]));

// Chat endpoints (session-based) – need session middleware even under API
Route::middleware(\Illuminate\Session\Middleware\StartSession::class)
    ->group(function () {
        Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'send']);
        Route::post('/chat/debug', function (\Illuminate\Http\Request $request) {
            $message = $request->input('message', '');
            \Log::debug('Debug chat stub', ['message' => $message]);
            return response()->json([
                'messages' => [
                    ['role' => 'assistant', 'content' => '[debug] Вы сказали: ' . $message]
                ]
            ]);
        });
        Route::get('/chat/history', [\App\Http\Controllers\ChatController::class, 'history']);
    });

// Web-push subscriptions
Route::post('/push/subscribe', [\App\Http\Controllers\Api\PushSubscriptionController::class, 'store']);
Route::delete('/push/unsubscribe', [\App\Http\Controllers\Api\PushSubscriptionController::class, 'destroy']);

// Product endpoints (api prefix is already applied)
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

// Proxy to NLWeb search_products (accepts both GET and POST)
// Unified ask endpoint (POST, SSE stream supported via ChatService)
Route::post('/v1/ask', [\App\Http\Controllers\AskController::class, 'ask']);

Route::match(['get','post'], '/search_products', function (\Illuminate\Http\Request $request) {
    $query = mb_strtolower($request->input('query', ''));
    $limit = (int) $request->input('limit', 5);
    $sort = $request->input('sort');
    if (empty($sort)) {
        $sort = 'price_asc';
    }

    $nlwebUrl = rtrim(config('services.product_api.url', 'http://localhost:8000/api/search_products'), '/');

    try {
        $response = \Illuminate\Support\Facades\Http::timeout(10)
            ->asJson()
            ->post($nlwebUrl, [
                'query' => $query,
                'limit' => $limit,
                'sort'  => $sort,
            ]);

        $json = $response->json();
        // Normalize: some APIs return `items` instead of `results`
        if (isset($json['items']) && !isset($json['results'])) {
            $json['results'] = $json['items'];
        }
        return $json;
    } catch (\Throwable $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});

// Новый тестовый маршрут для проверки function calling
Route::post('/test-function-call', function (Request $request, LlmGatewayService $llm) {
    $userMessage = $request->input('message', 'Ibuprofeno цена в базе');
    $sessionId = $request->input('session_id', 'test-session-' . uniqid());

    try {
        $toolManifest = (new \App\Services\ToolManifestService())->getToolsManifest();
        $response = $llm->chat([
            ['role' => 'user', 'content' => $userMessage]
        ], $toolManifest);

        return response()->json([
            'session_id' => $sessionId,
            'user_message' => $userMessage,
            'assistant_response' => $response,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});