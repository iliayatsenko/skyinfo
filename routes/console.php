<?php

use App\Services\SkymonitorsProcessor;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('process-skymonitors', function () {
    app()->get(SkymonitorsProcessor::class)->process();
})->purpose('Send wheather notifications for all skymonitors');

// Check if need to send notifications for all skymonitors every hour
Schedule::command('process-skymonitors')->hourly();
