<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void
    {
        $schedule->command('user:rotate-password')->monthlyOn(1, '08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        // ... middleware bawaan lain

        // tambahkan ini:
        'force.password.refresh' => \App\Http\Middleware\ForcePasswordRefresh::class,
    ];
}
