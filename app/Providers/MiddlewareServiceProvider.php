<?php

namespace App\Providers;

use App\Http\Middleware\SvelteKitAssetsMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Регистрируем middleware глобально
        app('router')->aliasMiddleware('svelte-assets', SvelteKitAssetsMiddleware::class);
        
        // Применяем middleware к маршрутам для ассетов
        app('router')->pushMiddlewareToGroup('web', SvelteKitAssetsMiddleware::class);
    }
}
