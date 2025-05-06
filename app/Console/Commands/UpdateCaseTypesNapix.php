<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\HCCaseTypeController;

class UpdateCaseTypesNapix extends Command
{
    protected $signature = 'napix:update-case-types';
    protected $description = 'Fetch case types from Napix and update the local database if new records exist';

    public function handle(): void
    {
        Log::info('[Scheduler] napix:update-case-types started at ' . now());

        try {
            // Call reusable logic from controller (returns array, not a response object)
            $controller = new HCCaseTypeController();
            $result = $controller->syncCaseTypesFromApi(); // shared method

            $inserted = $result['inserted'] ?? 0;
            $this->info("Case types updated successfully. Inserted: {$inserted}");
            Log::info("[Scheduler] Case types updated successfully. Inserted: {$inserted} at " . now());
        } catch (\Exception $e) {
            $errorMessage = '[Scheduler] Failed to update case types: ' . $e->getMessage();
            Log::error($errorMessage);
            $this->error('Error: ' . $e->getMessage());
        }

        Log::info('[Scheduler] napix:update-case-types finished at ' . now());
    }
}