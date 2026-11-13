<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule SLA monitoring to run every hour
Schedule::command('rfc:check-sla')->hourly();

// Schedule monthly report to run on the 1st of each month at 08:00
Schedule::command('report:monthly')->monthlyOn(1, '08:00');
