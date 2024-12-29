<?php
namespace App\Console;

use App\Jobs\SendScheduledEmail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
       
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            \Log::info('Scheduled email task is running'); 
            $details = [
                'email' => 'recipient_email@example.com',
                'subject' => 'hjlhj ',
                'title' => 'hjlhj!',
                'body' => ' Laravel   .'
            ];

            
            dispatch(new SendScheduledEmail($details));
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
