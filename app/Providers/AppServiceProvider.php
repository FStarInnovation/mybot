<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
        //
    }
}
