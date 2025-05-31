<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteScanController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\Controller; // LLM Bridge
use App\Http\Controllers\SupabaseController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\LlmBridgeController;
use App\Http\Controllers\ProductController;

Route::post('/scan-sites', [SiteScanController::class, 'scan']);
Route::get('/results', [ResultsController::class, 'index']);

Route::post('/llm', [Controller::class, 'queryLLM']);
Route::post('/llm/query', [SupabaseController::class, 'queryEmbedding']); // ← 
Route::post('/supabase-test', [SupabaseController::class, 'testQuery']);
Route::get('/supabase-test', [SupabaseController::class, 'testQuery']);

Route::post('/query', [LlmBridgeController::class, 'query'])->name('api.query');
Route::get('/ping', fn() => response()->json(['pong' => true]));

// Product endpoints (api prefix is already applied)
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);