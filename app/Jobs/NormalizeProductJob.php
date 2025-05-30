<?php

namespace App\Jobs;

use App\Models\RawProduct;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NormalizeProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 60; // seconds

    public function __construct(public int $rawProductId)
    {
        $this->onQueue('normalize');
    }

    public function handle(): void
    {
        $raw = RawProduct::find($this->rawProductId);
        if (!$raw || $raw->status === 'done') {
            return;
        }

        try {
            $data = $raw->data ?? [];

            // 1. Category (simple by hashtag or metadata)
            $categoryName = $raw->hashtag ?? $data['category'] ?? 'uncategorized';
            $categorySlug = Str::slug($categoryName);
            $category = Category::firstOrCreate(
                ['slug' => $categorySlug],
                ['name' => $categoryName]
            );

            // 2. Product basic fields
            $product = Product::updateOrCreate([
                'source_id' => $raw->id,
            ], [
                'title' => $raw->title,
                'slug' => Str::slug(($raw->title ?: 'product').'-'.$raw->id),
                'price' => $raw->price_num ?? 0,
                'url' => $raw->url,
                'category_id' => $category->id,
            ]);

            // 3. Images (if any)
            if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $url) {
                    ProductImage::firstOrCreate([
                        'product_id' => $product->id,
                        'url' => $url,
                    ]);
                }
            }

            // 4. Embedding (placeholder)
            if ($raw->embedding) {
                // store embedding to vector store later
            }

            $raw->update([
                'status' => 'done',
                'processed_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('NormalizeProductJob failed: ' . $e->getMessage(), [
                'raw_id' => $this->rawProductId,
            ]);
            $raw?->update(['status' => 'error']);
            throw $e;
        }
    }
}
