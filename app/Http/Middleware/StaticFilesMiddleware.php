<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class StaticFilesMiddleware
{
    /**
     * Map of file extensions to MIME types.
     *
     * @var array
     */
    protected $mimeTypes = [
        'js' => 'text/javascript',
        'css' => 'text/css',
        'json' => 'application/json',
        'webmanifest' => 'application/manifest+json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        $fileName = basename($path); // Get the filename, e.g., 'pwa-192x192.png' or 'app.js'
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // Get extension from filename, e.g., 'png' or 'js'

        // 1. Check for PWA assets at the root
        // - manifest.webmanifest
        // - pwa-*.png / maskable-icon-*.png
        // - sw.js / registerSW.js (if they are at the root and have .js extension)

        if ($path === 'manifest.webmanifest') {
            if (isset($this->mimeTypes['webmanifest'])) {
                return $this->serveFile($request, $path, $this->mimeTypes['webmanifest']);
            }
        }

        if ($extension === 'png' && (Str::startsWith($fileName, 'pwa-') || Str::startsWith($fileName, 'maskable-icon-'))) {
             if (isset($this->mimeTypes['png'])) {
                return $this->serveFile($request, $path, $this->mimeTypes['png']);
            }
        }
        
        if (($fileName === 'sw.js' || $fileName === 'registerSW.js') && $extension === 'js') {
            if (isset($this->mimeTypes['js'])) {
                 return $this->serveFile($request, $path, $this->mimeTypes['js']);
            }
        }

        // 2. Check for assets under 'build/' directory (existing logic)
        //    The $extension variable is already calculated above.
        if (Str::startsWith($path, 'build/')) {
            // For files without extension within 'build/', but with known names (e.g., 'registerSW')
            if (empty($extension)) {
                if (Str::endsWith($path, 'registerSW') || Str::endsWith($path, 'sw')) {
                    return $this->serveFile($request, $path, 'text/javascript');
                }
            }
            
            // For files with known extensions within 'build/'
            if (!empty($extension) && isset($this->mimeTypes[$extension])) {
                return $this->serveFile($request, $path, $this->mimeTypes[$extension]);
            }
            
            // Fallbacks for SvelteKit specific paths within 'build/' if not caught by extension.
            // These might be for files that don't have standard extensions or are in nested structures.
            if (Str::contains($path, '_app/immutable/entry/') || 
                Str::contains($path, '_app/immutable/chunks/')) {
                // Assuming these are JavaScript if not otherwise typed
                return $this->serveFile($request, $path, 'text/javascript');
            }
            
            if (Str::contains($path, '_app/immutable/assets/')) {
                 // Assuming these are CSS if not otherwise typed
                return $this->serveFile($request, $path, 'text/css');
            }
        }
        
        return $next($request);
    }
    
    /**
     * Serve a static file with the proper MIME type.
     */
    protected function serveFile(Request $request, string $path, string $mimeType): Response
    {
        $fullPath = public_path($path);
        
        if (file_exists($fullPath) && !is_dir($fullPath)) {
            $headers = [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=2592000', // 30 days
                'X-Content-Type-Options' => 'nosniff',
            ];

            // Для JavaScript файлов добавляем тот же CSP, что и HTML,
            // чтобы сервис‑воркер и другие воркеры могли выполнять eval.
            if ($mimeType === 'text/javascript') {
                $headers['Content-Security-Policy'] =
                    "default-src 'self'; "
                    . "script-src 'self' 'unsafe-eval' 'wasm-unsafe-eval'; "
                    . "style-src 'self' 'unsafe-inline'; "
                    . "img-src 'self' data: https:;";
            }

            return response()->file($fullPath, $headers);
        }
        
        // Fallback для файлов, которые не найдены
        return response()->json(['error' => 'File not found'], 404);
    }
}
