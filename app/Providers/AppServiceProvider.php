<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kirschbaum\Loop\Facades\Loop;
use App\Loop\Tools\ImportCatalogTool;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\Memory\ShortTermMemoryService::class);
        $this->app->singleton(\App\Services\Memory\LongTermMemoryService::class);
        $this->app->singleton(\App\Services\MemoryService::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register MCP tools so they are available on every request
        // Используем полное имя класса для доступа к сервису Loop
        if (app()->bound('Kirschbaum\\Loop\\Loop')) {
            app('Kirschbaum\\Loop\\Loop')->tool(\App\Loop\Tools\ImportCatalogTool::make());
        }
        if (! function_exists('svelte_asset')) {
            /**
             * Return URL of the first file matching a glob pattern in public/build.
             */
            function svelte_asset(string $pattern): string
            {
                $matches = glob(public_path($pattern));
                if (! $matches) {
                    return '';
                }
                $path     = $matches[0];
                $relative = ltrim(str_replace(public_path(), '', $path), '/');
                return asset($relative);
            }
        }
    }
}
