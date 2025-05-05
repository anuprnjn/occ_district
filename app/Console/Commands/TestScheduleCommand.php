<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestScheduleCommand extends Command
{
    protected $signature = 'test:schedule';
    protected $description = 'Test Laravel 11 scheduler';

    public function handle()
    {
        Log::info('Laravel 11 Scheduler ran successfully at ' . now());
        $this->info('TestScheduleCommand executed at ' . now());
    }
}