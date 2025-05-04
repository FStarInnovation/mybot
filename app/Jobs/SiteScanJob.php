<?php

namespace App\Jobs;

use App\Services\ProductEmbeddingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SiteScanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $operator;
    protected string $query;

    public function __construct(string $operator, string $query)
    {
        $this->operator = $operator;
        $this->query = $query;
    }

    public function handle(): void
    {
        Log::info("Запуск сканирования: {$this->operator}", ['query' => $this->query]);

        // 🔻 Заглушка: здесь ты должен получать реальные товары
        $results = $this->fetchProducts($this->operator, $this->query);

        // 🔁 Индексация каждого товара в Upstash
        foreach ($results as $product) {
            ProductEmbeddingService::indexProduct($product);
        }

        // 💾 Кэшируем результат
        $all = Cache::get('scan_results', []);
        $all[$this->operator] = [
            'query'   => $this->query,
            'results' => $results,
            'ran_at'  => now()->toDateTimeString(),
        ];
        Cache::put('scan_results', $all, 3600);
    }

    // Пример заглушки, подключи здесь реальную логику
    private function fetchProducts(string $operator, string $query): array
    {
        // TODO: Подключи парсеры (Farmacity и т.п.)
        return []; // ← подставь реальные данные
    }
}