<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command for scheduling';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Scheduled Task Executed!');
    }
}
