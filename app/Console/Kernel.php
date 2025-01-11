<?php

namespace App\Console;
use App\Console\TestMessage;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
{
    $schedule->command('test:message')->everyMinute();
}


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    
    
        $this->commands([
            Commands\TestMessage::class, 
        ]);
    
        require base_path('routes/console.php');
    }
    
}
