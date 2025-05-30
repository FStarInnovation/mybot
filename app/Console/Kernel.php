<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Зарегистрированные artisan-команды.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ProcessSiteScans::class,
        \App\Console\Commands\CheckDatabaseConnection::class,
        \App\Console\Commands\ImportFarmaCommand::class,
    ];

    /**
     * Планировщик задач cron.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Основной обработчик сканов сайтов
        $schedule->command('scan:process --count=10')->everyMinute();

        // Проверка подключения к Supabase — каждые 5 минут
        $schedule->command('supabase:check')->everyFiveMinutes()->withoutOverlapping();
    }

    /**
     * Регистрация пользовательских artisan-команд.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}