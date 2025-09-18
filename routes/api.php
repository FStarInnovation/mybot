<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\SiteScanController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\Controller; // LLM Bridge
use App\Http\Controllers\SupabaseController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\LlmBridgeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PushController;
use App\Http\Controllers\HealthController;

Route::post('/scan-sites', [SiteScanController::class, 'scan']);
Route::get('/results', [ResultsController::class, 'index']);

Route::post('/llm', [Controller::class, 'queryLLM']);
Route::post('/llm/query', [SupabaseController::class, 'queryEmbedding']); // ← 
Route::post('/supabase-test', [SupabaseController::class, 'testQuery']);
Route::get('/supabase-test', [SupabaseController::class, 'testQuery']);

Route::post('/query', [LlmBridgeController::class, 'query'])->name('api.query');
Route::get('/ping', fn() => response()->json(['pong' => true]));

// MCP queue & worker health-check
Route::get('/health/mcp', [HealthController::class, 'mcp'])->name('health.mcp');

// NLWEB streaming proxy (SSE)
Route::get('/ask', [\App\Http\Controllers\GatewayProxyController::class, 'stream'])->name('api.ask.stream');
// Synchronous JSON
Route::post('/tool/ask', [\App\Http\Controllers\GatewayProxyController::class, 'askSync'])->name('api.ask.sync');

// Chat endpoints (session-based) – need session middleware even under API
Route::middleware(\Illuminate\Session\Middleware\StartSession::class)
    ->group(function () {
        Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'send']);
        Route::get('/chat/history', [\App\Http\Controllers\ChatController::class, 'history']);
    });

// Web-push subscriptions
Route::post('/push/subscribe', [\App\Http\Controllers\Api\PushSubscriptionController::class, 'store']);
Route::delete('/push/unsubscribe', [\App\Http\Controllers\Api\PushSubscriptionController::class, 'destroy']);

// Diagnostic route for RunPod testing
Route::get('/diag/runpod', function () {
    $apiUrl = env('RUNPOD_API_URL');
    
    // Test chat endpoint
    $chatResponse = Http::timeout(30)->post("{$apiUrl}/chat", [
        'model' => 'llama',
        'messages' => [
            ['role' => 'user', 'content' => 'Hola, ¿qué es ibuprofeno?']
        ],
        'stream' => false,
        'temperature' => 0.5,
        'max_tokens' => 100
    ]);
    
    // Test embedding endpoint
    $embedResponse = Http::timeout(30)->post("{$apiUrl}/embedding", [
        'input' => ['Ibuprofeno 400 mg'],
        'model' => 'jina-embeddings-v2-base-es'
    ]);
    
    return response()->json([
        'api_url' => $apiUrl,
        'chat' => [
            'status' => $chatResponse->status(),
            'success' => $chatResponse->successful(),
            'body' => $chatResponse->successful()
                ? $chatResponse->json()
                : $chatResponse->body(),
            'error' => $chatResponse->successful() ? null : $chatResponse->body()
        ],
        'embedding' => [
            'status' => $embedResponse->status(),
            'success' => $embedResponse->successful(),
            'body' => $embedResponse->successful()
                ? $embedResponse->json()
                : $embedResponse->body(),
            'error' => $embedResponse->successful() ? null : $embedResponse->body()
        ]
    ], 200, [], JSON_UNESCAPED_UNICODE);
});

// Product endpoints (api prefix is already applied)
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);