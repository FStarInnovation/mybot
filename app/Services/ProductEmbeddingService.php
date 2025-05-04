<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Upstash\Vector\Laravel\Facades\Vector;
use Upstash\Vector\VectorUpsert;

class ProductEmbeddingService
{
    public static function indexProduct(array $product): void
    {
        if (!isset($product['embedding']) || count($product['embedding']) !== 754) {
            Log::warning("Пропущен продукт с некорректным embedding", ['url' => $product['url'] ?? null]);
            return;
        }

        $id = $product['url'] ?? md5(json_encode($product));

        $metadata = [
            'title'           => $product['title'] ?? '',
            'brand'           => $product['brand'] ?? '',
            'price'           => $product['price'] ?? null,
            'originalPrice'   => $product['originalPrice'] ?? null,
            'discount'        => $product['discount'] ?? null,
            'pharmacy'        => $product['pharmacy'] ?? '',
            'timestamp'       => $product['timestamp'] ?? now()->toISOString(),
            'description_md'  => $product['markdown'] ?? null,
        ];

        try {
            $vec = new VectorUpsert(
                $id,
                $product['embedding'],
                null,
                $metadata
            );

            Vector::upsert($vec);
        } catch (\Throwable $e) {
            Log::error("Ошибка при индексации товара: " . $e->getMessage(), ['id' => $id]);
        }
    }
}