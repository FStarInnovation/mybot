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

Route::post('/scan-sites', [SiteScanController::class, 'scan']);
Route::get('/results', [ResultsController::class, 'index']);

Route::post('/llm', [Controller::class, 'queryLLM']);
Route::post('/llm/query', [SupabaseController::class, 'queryEmbedding']); // ← 
Route::post('/supabase-test', [SupabaseController::class, 'testQuery']);
Route::get('/supabase-test', [SupabaseController::class, 'testQuery']);

Route::post('/query', [LlmBridgeController::class, 'query'])->name('api.query');
Route::get('/ping', fn() => response()->json(['pong' => true]));

// Chat endpoints (session-based)
Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'send']);
Route::get('/chat/history', [\App\Http\Controllers\ChatController::class, 'history']);

// Web-push subscriptions
Route::post('/push/subscribe', [\App\Http\Controllers\Api\PushSubscriptionController::class, 'store']);
Route::delete('/push/unsubscribe', [\App\Http\Controllers\Api\PushSubscriptionController::class, 'destroy']);

// Product endpoints (api prefix is already applied)
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);