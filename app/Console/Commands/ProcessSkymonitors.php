<?php

namespace App\Console\Commands;

use App\Models\Skymonitor;
use App\Notifications\SkymonitorAlert;
use App\Services\SkymonitorsProcessor;
use App\Services\Weather\WeatherProviderException;
use App\Services\Weather\WeatherProviderInterface;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;

class ProcessSkymonitors extends Command
{
    protected $signature = 'app:process-skymonitors';

    protected $description = 'Send weather notifications for all skymonitors';

    public function __construct(
        private readonly SkymonitorsProcessor $processor,
        private readonly WeatherProviderInterface $weatherProvider
    ) {
        parent::__construct();
    }

    public function handle() {
        Skymonitor::chunk(
            50,
            function (Collection $skymonitors) {
                foreach ($skymonitors as $skymonitor) {
                    try {
                        $weather = $this->weatherProvider->getWeather($skymonitor->city);
                        $this->processor->process($skymonitor, $weather);
                    } catch (WeatherProviderException $e) {
                        Notification::routes([
                            'mail' => $skymonitor->email ?: null,
                            'vonage' => $skymonitor->phone ?: null,
                        ])->notify(new SkymonitorAlert($e->getMessage()));
                    }
                }
            }
        );
    }
}
