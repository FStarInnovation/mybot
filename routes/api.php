<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteScanController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\Controller; // LLM Bridge
use App\Http\Controllers\SupabaseController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\LlmBridgeController;

Route::post('/scan-sites', [SiteScanController::class, 'scan']);
Route::get('/results', [ResultsController::class, 'index']);

Route::post('/llm', [Controller::class, 'queryLLM']);
Route::post('/supabase-test', [SupabaseController::class, 'testQuery']);

Route::get('/supabase-test', [SupabaseController::class, 'testQuery']);

Route::post('/query', [LlmBridgeController::class, 'query'])->name('api.query');