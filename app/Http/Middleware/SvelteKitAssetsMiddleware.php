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
        
        // Продолжаем обработку запроса и получаем ответ Laravel
        $response = $next($request);

        // Для HTML‑ответов добавляем CSP‑заголовок,
        // разрешая 'unsafe-eval' (нужен Workbox/SvelteKit) и 'unsafe-inline' для стилей.
        if (Str::startsWith($response->headers->get('Content-Type'), 'text/html')) {
            $csp = "default-src 'self'; "
                 . "script-src 'self' 'unsafe-eval' 'wasm-unsafe-eval'; "
                 . "style-src 'self' 'unsafe-inline'; "
                 . "img-src 'self' data: https:;";
            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
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
