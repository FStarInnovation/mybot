<?php

namespace App\Console\Commands;

use App\Jobs\NormalizeProductJob;
use App\Models\RawProduct;
use Illuminate\Console\Command;

class NormalizeRawCommand extends Command
{
    protected $signature = 'raw:normalize {--limit=1000}';

    protected $description = 'Dispatch NormalizeProductJob for raw_products with status=imported';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $query = RawProduct::where('status', 'imported')->limit($limit)->pluck('id');
        $count = 0;
        foreach ($query as $id) {
            NormalizeProductJob::dispatch($id);
            $count++;
        }
        $this->info("Dispatched {$count} jobs");
        return Command::SUCCESS;
    }
}
