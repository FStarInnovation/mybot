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
            // TODO: parse HTML, extract product data, dispatch normalization job
        } else {
            Log::warning('Failed to fetch product', [
                'url'    => $this->url,
                'status' => $resp->status(),
            ]);
        }
    }
}
