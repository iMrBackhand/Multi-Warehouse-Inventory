<?php

// Laravel 11/12: there is no app/Console/Kernel.php by default.
// Add this to routes/console.php instead.

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::command('backup:run')->daily()->at('01:00')
    ->onSuccess(function () {
        Log::info('Scheduled database backup completed successfully.');
    })
    ->onFailure(function () {
        Log::error('Scheduled database backup failed.');
    });

// Applies the cleanup strategy defined in config/backup.php
// (keep_all_backups_for_days, keep_daily_backups_for_days, etc.)
Schedule::command('backup:clean')->daily()->at('01:30');
