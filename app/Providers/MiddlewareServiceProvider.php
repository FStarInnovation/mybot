<?php

namespace App\Providers;

use App\Http\Middleware\SvelteKitAssetsMiddleware;
use App\Http\Middleware\StaticFilesMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * All middleware to be registered.
     *
     * @var array
     */
    protected $middleware = [
        StaticFilesMiddleware::class,
    ];

    /**
     * Route middleware groups to be registered.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            StaticFilesMiddleware::class,
        ],
    ];

    /**
     * Named middleware aliases to be registered.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'static-files' => StaticFilesMiddleware::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Ничего не регистрируем на этапе register
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerMiddleware();
        $this->registerMiddlewareGroups();
        $this->registerRouteMiddleware();
    }
    
    /**
     * Register global middleware.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $router = $this->app['router'];
        
        foreach ($this->middleware as $middleware) {
            $router->middleware($middleware);
        }
    }
    
    /**
     * Register middleware groups.
     *
     * @return void
     */
    protected function registerMiddlewareGroups()
    {
        $router = $this->app['router'];
        
        foreach ($this->middlewareGroups as $group => $middlewares) {
            foreach ($middlewares as $middleware) {
                $router->pushMiddlewareToGroup($group, $middleware);
            }
        }
    }
    
    /**
     * Register route middleware aliases.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        $router = $this->app['router'];
        
        foreach ($this->routeMiddleware as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
    }
}
