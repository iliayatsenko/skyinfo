<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Skymonitor;
use App\Notifications\SkymonitorAlert;
use App\Notifications\WeatherAlert;
use App\Services\Weather\WeatherProviderException;
use App\Services\Weather\WeatherProviderInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;

class SkymonitorsProcessor
{
    public function __construct(
        private readonly WeatherProviderInterface $weatherProvider,
    ) {}

    public function process()
    {
        Skymonitor::chunk(50, function (Collection $skymonitors) {
            foreach ($skymonitors as $skymonitor) {
                /** @var Skymonitor $skymonitor */
                try {
                    $weather = $this->weatherProvider->getWeather($skymonitor->city);
                } catch (WeatherProviderException $e) {
                    Notification::routes([
                        'mail' => $skymonitor->email ?: null,
                        'vonage' => $skymonitor->phone ?: null,
                    ])->notify(new SkymonitorAlert($e->getMessage()));
                }

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
        });
    }
}
