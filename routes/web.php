<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\SiteScanController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\Controller; // LLM Bridge


// Маршрут для корневого пути
Route::get('/', function () {
    return view('welcome'); // Возвращает resources/views/welcome.blade.php
});

// Универсальные маршруты (web)
Route::post('/scan-sites', [SiteScanController::class, 'scan']);
Route::get('/results', [ResultsController::class, 'index']);
Route::post('/llm', [Controller::class, 'queryLLM']);

use App\Services\SupabaseService;

Route::match(['get', 'post'], '/supabase-test', function () {
    $service = app(SupabaseService::class);

    try {
        $data = $service->get('farma', [
            'select' => 'title,price_num',
            'order' => 'price_num.desc',
            'limit' => 5
        ]);

        return response()->json($data);
    } catch (\Throwable $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 500);
    }
});

// Маршрут для очистки кеша
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return '✅ Laravel cache cleared!';
});