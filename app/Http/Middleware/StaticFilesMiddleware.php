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
        
        // Проверяем только запросы к директории build
        if (Str::startsWith($path, 'build/')) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            
            // Для файлов без расширения, но с известными именами
            if (empty($extension)) {
                if (Str::endsWith($path, 'registerSW') || Str::endsWith($path, 'sw')) {
                    return $this->serveFile($request, $path, 'text/javascript');
                }
            }
            
            // Для файлов с известными расширениями
            if (isset($this->mimeTypes[$extension])) {
                return $this->serveFile($request, $path, $this->mimeTypes[$extension]);
            }
            
            // Для SvelteKit JavaScript файлов
            if (Str::contains($path, '_app/immutable/entry/') || 
                Str::contains($path, '_app/immutable/chunks/')) {
                return $this->serveFile($request, $path, 'text/javascript');
            }
            
            // Для SvelteKit CSS файлов
            if (Str::contains($path, '_app/immutable/assets/')) {
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
            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=2592000', // 30 days
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }
        
        // Fallback для файлов, которые не найдены
        return response()->json(['error' => 'File not found'], 404);
    }
}
