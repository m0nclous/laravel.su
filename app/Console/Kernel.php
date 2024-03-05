<?php

namespace App\Console;

use App\Models\CodeSnippet;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('app:checkout-latest-docs')->daily();
        $schedule->command('app:compare-document')->daily();
        $schedule->command('app:update-packages')->daily();
        $schedule->command('telescope:prune')->daily();

        // вот не знаю, оо тут нужно или нет?
        $schedule->command('model:prune', [
            '--model' => [
                CodeSnippet::class,
            ],
        ])->daily();

        $schedule->command('sqlite:optimize')->everyMinute();

        $schedule->command('app:achievements-translation')
            ->weeklyOn([Schedule::SATURDAY, Schedule::SUNDAY]);

        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('01:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
