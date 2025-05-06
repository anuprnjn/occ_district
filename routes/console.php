<?php 
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Define the macro for getting case types from napix api to database and update database
Schedule::macro('napixScheduler', function () {
    $this->command('napix:update-case-types')
        ->everyTenSeconds() 
        ->appendOutputTo(storage_path('logs/scheduler.log'));
});

// Invoke the macro
Schedule::napixScheduler();