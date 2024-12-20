<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Skymonitor;
use App\Notifications\WeatherAlert;
use App\Services\Weather\WeatherDto;
use Illuminate\Support\Facades\Notification;

class SkymonitorsProcessor
{
    public function process(SkyMonitor $skymonitor, WeatherDto $weather): void
    {
        $shouldNotify = false;
        $reasons = [];

        if ($weather->precipitationMm >= $skymonitor->precipitation_threshold) {
            $shouldNotify = true;
            $reasons[] = sprintf('High precipitation: %s mm', $weather->precipitationMm);
        }

        if ($weather->uvIndex >= $skymonitor->uv_index_threshold) {
            $shouldNotify = true;
            $reasons[] = sprintf('High UV index: %s', $weather->uvIndex);
        }

        if ($shouldNotify) {
            Notification::routes([
                'mail' => $skymonitor->email ?: null,
                'vonage' => $skymonitor->phone ?: null,
            ])->notify(
                new WeatherAlert(
                    sprintf('Weather conditions in %s are not good: %s', $skymonitor->city, implode('; ', $reasons))
                )
            );
        }
    }
}
