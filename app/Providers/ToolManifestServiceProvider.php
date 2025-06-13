<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ToolManifestService;

class ToolManifestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ToolManifestService::class, function () {
            return new ToolManifestService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
