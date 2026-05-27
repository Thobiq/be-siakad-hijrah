<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// $schedule->command('backup:clean')->dailyAt('01:00');
// $schedule->command('backup:run --only-db')->dailyAt('01:30');