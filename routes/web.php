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