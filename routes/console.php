<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run the cleanup every day at midnight
Schedule::command('asl:cleanup-orders')->daily();

// Check stock every morning at 8:00 AM
Schedule::command('app:check-low-stock')->dailyAt('08:00');
