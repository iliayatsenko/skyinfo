<?php
declare(strict_types=1);

namespace App\Services;

use App\Jobs\EmailNotification;
use App\Jobs\PhoneNotification;
use App\Models\Skymonitor;
use App\Services\Weather\WeatherProviderException;
use App\Services\Weather\WeatherProviderInterface;
use Illuminate\Database\Eloquent\Collection;

class SkymonitorsProcessor
{
    public function __construct(
        private readonly WeatherProviderInterface $weatherProvider,
    )
    {

    }

    public function process()
    {
        Skymonitor::chunk(50, function (Collection $skymonitors) {
            foreach ($skymonitors as $skymonitor) {
                /** @var Skymonitor $skymonitor */

                try {
                    $weather = $this->weatherProvider->getWeather($skymonitor->city);
                } catch (WeatherProviderException $e) {
                    EmailNotification::dispatchUnless(
                        empty($skymonitor->email),
                        $skymonitor->email,
                        sprintf('Skymonitor for city %s failed to get weather data: %s', $skymonitor->city, $e->getMessage())
                    );

                    PhoneNotification::dispatchUnless(
                        empty($skymonitor->phone),
                        $skymonitor->phone,
                        sprintf('Skymonitor for city %s failed to get weather data: %s', $skymonitor->city, $e->getMessage())
                    );
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
                    EmailNotification::dispatchUnless(
                        empty($skymonitor->email),
                        $skymonitor->email,
                        sprintf('Weather conditions in your city are not good: %s', implode('; ', $reasons))
                    );

                    PhoneNotification::dispatchUnless(
                        empty($skymonitor->phone),
                        $skymonitor->phone,
                        sprintf('Weather conditions in your city are not good: %s', implode('; ', $reasons))
                    );
                }
            }
        });
    }
}
