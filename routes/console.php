<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Define macro for NAPIX case type sync
Schedule::macro('napixScheduler', function () {
    // $this->command('napix:update-case-types')
    //     ->everyTenSeconds()
    //     ->appendOutputTo(storage_path('logs/scheduler.log'));

    $this->command('napix:sync-dc-case-types')
        ->everyTenSeconds()
        ->appendOutputTo(storage_path('logs/scheduler.log'));
});

// Invoke macro
Schedule::napixScheduler();