<?php

use Illuminate\Support\Facades\Schedule;

// Check if need to send notifications for all skymonitors every hour
Schedule::command('app:process-skymonitors')->hourly();
