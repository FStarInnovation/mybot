<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SvelteKitAssetsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        
        // Проверяем, что это запрос к ассетам SvelteKit
        if (Str::startsWith($path, 'build/') || $path === 'build') {
            $fullPath = public_path($path);
            
            // Если файл существует
            if (file_exists($fullPath) && !is_dir($fullPath)) {
                $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
                $mimeType = $this->getMimeType($extension, $path);
                
                // Если это файл с расширением, отдаем его с правильным MIME-типом
                if ($mimeType) {
                    return response()->file($fullPath, [
                        'Content-Type' => $mimeType,
                    ]);
                }
            }
        }
        
        return $next($request);
    }
    
    /**
     * Определяем MIME-тип на основе расширения файла
     */
    private function getMimeType(string $extension, string $path): ?string
    {
        $mimeTypes = [
            'js' => 'text/javascript',
            'mjs' => 'text/javascript',
            'css' => 'text/css',
            'json' => 'application/json',
            'webmanifest' => 'application/manifest+json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
        ];
        
        // Специальные случаи для файлов без расширения
        if (empty($extension)) {
            if (Str::endsWith($path, 'registerSW') || Str::endsWith($path, 'sw')) {
                return 'text/javascript';
            }
        }
        
        return $mimeTypes[$extension] ?? null;
    }
}
