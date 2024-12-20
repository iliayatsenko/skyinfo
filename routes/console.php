<?php

use App\Services\SkymonitorsProcessor;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('process-skymonitors', function () {
    app()->get(SkymonitorsProcessor::class)->process();
})->purpose('Send wheather notifications for all skymonitors');

//Schedule::call(function () {
//    app()->get(SkymonitorsProcessor::class)->process();
//})->everySecond();
