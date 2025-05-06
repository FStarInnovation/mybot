<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckDatabaseConnection extends Command
{
    protected $signature = 'check:db';
    protected $description = 'Check database connection';

    public function handle()
    {
        try {
            DB::connection()->getPdo();
            $this->info('✅ Connected to database: ' . DB::connection()->getDatabaseName());
        } catch (Throwable $e) {
            $this->error('❌ Could not connect to the database.');
            $this->error($e->getMessage());
        }
    }
}