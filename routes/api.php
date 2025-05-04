<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResultsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Здесь определяются маршруты, доступные по префиксу /api.
|
*/

Route::middleware('api')->group(function () {
    // POST /api/scan-sites  → запускает сканирование
    Route::post('/scan-sites', [\App\Http\Controllers\SiteScanController::class, 'scan']);

    // GET  /api/results     → возвращает результаты
    Route::get('/results',   [ResultsController::class,        'index']);
});