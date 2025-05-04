<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Путь на домашнюю страницу приложения после авторизации.
     */
    public const HOME = '/home';

    /**
     * Регистрируем маршруты приложения.
     */
    public function boot(): void
    {
        // Настройка rate limiting для API
        $this->configureRateLimiting();

        // Подключаем маршруты
        $this->routes(function () {
            // API маршруты (routes/api.php)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Web маршруты (routes/web.php)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Настройка лимитов запросов для API.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                optional($request->user())->id ?: $request->ip()
            );
        });
    }
}