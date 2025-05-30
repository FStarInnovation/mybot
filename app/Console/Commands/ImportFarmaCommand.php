<?php

namespace App\Console\Commands;

use App\Models\RawProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Log;
use Laravel\Telescope\Telescope;

class ImportFarmaCommand extends Command
{
    protected $signature = 'import:farma {--chunk=1000}';

    protected $description = 'Import test data from farma table (Neon branch) into raw_products for pipeline testing';

    public function handle(): int
    {
        $chunk = (int) $this->option('chunk');

        // Disable Telescope & query log to reduce memory usage during bulk import
        if (class_exists(Telescope::class)) {
            Telescope::stopRecording();
        }
        DB::disableQueryLog();

        $this->info("Importing in chunks of {$chunk}...");

        // Use source connection to farma (default)
        LazyCollection::make(function () {
            yield from DB::connection('farma')->table('farma')->orderBy('id')->cursor();
        })->chunk($chunk)->each(function ($rows) {
            $insert = [];
            foreach ($rows as $row) {
                $insert[] = [
                    'id' => $row->id,
                    'created_at' => $row->created_at,
                    'updated_at' => now(),
                    'data' => $row->data,
                    'metadata' => $row->metadata,
                    'gtin' => $row->gtin,
                    'hashtag' => $row->hashtag,
                    'price' => $row->price,
                    'price_num' => $row->price_num,
                    'timestamp' => $row->timestamp,
                    'title' => $row->title,
                    'url' => $row->url,
                    'embedding' => $row->embedding,
                    'status' => 'imported',
                ];
            }

            RawProduct::upsert($insert, ['id']);
        });

        $this->info('Import completed');
        return Command::SUCCESS;
    }
}
