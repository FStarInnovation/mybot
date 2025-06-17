<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class ImportFarmacityCatalog implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(private ?int $limit = null) {}

    public function handle(): void
    {
        $sitemapIndex = 'https://www.farmacity.com/sitemap.xml';

        $response = Http::get($sitemapIndex);
        if (! $response->ok()) {
            Log::error('Failed to fetch sitemap index', [
                'status' => $response->status(),
            ]);
            return;
        }

        $xml = @simplexml_load_string($response->body());
        if (! $xml) {
            Log::error('Invalid XML in sitemap index');
            return;
        }

        $productMaps = Collection::make($xml->sitemap ?? [])
            ->pluck('loc')
            ->map(fn ($loc) => (string) $loc)
            ->filter(fn ($loc) => str_contains($loc, 'product-'))
            ->values();

        $count = 0;
        foreach ($productMaps as $mapUrl) {
            $resp = Http::get($mapUrl);
            if (! $resp->ok()) {
                continue; // skip broken sitemap link
            }
            $urlsXml = @simplexml_load_string($resp->body());
            if (! $urlsXml) {
                continue;
            }
            foreach ($urlsXml->url as $u) {
                if ($this->limit && $count >= $this->limit) {
                    break 2; // exit both loops
                }
                $pdpUrl = (string) $u->loc;
                $this->dispatchParser($pdpUrl);
                $count++;
            }
        }

        Log::info('Queued PDP URLs from Farmacity sitemap', [
            'count' => $count,
        ]);
    }

    private function dispatchParser(string $url): void
    {
        // You might already have a job/class that processes a single product page.
        // We'll create a stub if not.
        ParseFarmacityProduct::dispatch($url);
    }
}
