<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Simple placeholder job that fetches the PDP URL and logs its length.
 * Replace body with real parser later.
 */
class ParseFarmacityProduct implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(private string $url) {}

    public function handle(): void
    {
        $resp = Http::get($this->url);
        if ($resp->ok()) {
            Log::info('Fetched product page', [
                'url'   => $this->url,
                'bytes' => strlen($resp->body()),
            ]);
            
            // Отправка URL в RunPod API для дальнейшей обработки
            try {
                $runpodApiUrl = rtrim(config('services.gateway.base'), '/') . '/tool/crawl_single_page';
                
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json'
                    ])
                    ->post($runpodApiUrl, [
                        'url' => $this->url,
                        // Можно добавить дополнительные параметры по необходимости
                    ]);
                
                if ($response->successful()) {
                    Log::info('Successfully sent URL to RunPod API', [
                        'url' => $this->url,
                        'status' => $response->status(),
                        'response' => $response->json(),
                    ]);
                } else {
                    Log::error('Failed to send URL to RunPod API', [
                        'url' => $this->url,
                        'status' => $response->status(),
                        'error' => $response->body(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Exception sending URL to RunPod API', [
                    'url' => $this->url,
                    'exception' => $e->getMessage(),
                ]);
            }
        } else {
            Log::warning('Failed to fetch product', [
                'url'    => $this->url,
                'status' => $resp->status(),
            ]);
        }
    }
}
