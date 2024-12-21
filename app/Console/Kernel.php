<?php
namespace App\Console;

use App\Console\Commands\SendAutomaticEmails;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * 
     *
     * @var array
     */
    protected $commands = [
        SendAutomaticEmails::class,
    ];

    /**
     * 
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
        $schedule->command('send:emails')->everyMinute();  
    }

    /**
     *
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
