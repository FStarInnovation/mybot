<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Jobs\ProcessSiteScan;

class ProcessSiteScans extends Command
{
    protected $signature = 'scan:process {--count=10}';
    protected $description = 'Consume site_scans stream and dispatch ProcessSiteScan jobs';

    public function handle()
    {
        $streamKey = 'site_scans';
        $group     = 'scanners';
        $consumer  = 'scanner_1';
        $count     = (int) $this->option('count');

        $redis = Redis::connection('upstash');

        // Создаём группу, если её ещё нет
        try {
            $redis->xgroup('CREATE', $streamKey, $group, '0', true);
        } catch (\Exception $e) {}

        // Читаем новые записи (макс. $count, блок до 1с)
        $entries = $redis->xreadgroup(
            'GROUP', $group, $consumer,
            'COUNT', $count,
            'BLOCK', 1000,
            'STREAMS', $streamKey, '>'
        );

        if (empty($entries)) {
            return;
        }

        foreach ($entries[0][1] as $entry) {
            [$id, $fields] = $entry;
            $data = [];
            for ($i = 0; $i < count($fields); $i += 2) {
                $data[$fields[$i]] = $fields[$i + 1];
            }

            // Диспетчим Job для дальнейшей обработки
            ProcessSiteScan::dispatch($data['operator'], $data['query'])
                ->onConnection('upstash');

            // Подтверждаем обработку
            $redis->xack($streamKey, $group, [$id]);
        }
    }
}