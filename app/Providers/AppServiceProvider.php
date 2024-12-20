<?php

namespace App\Providers;

use App\Http\Integrations\WeatherApi\WeatherApiConnector;
use App\Services\SkymonitorsProcessor;
use App\Services\Weather\WeatherApiWeatherProvider;
use App\Services\Weather\WeatherProviderInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WeatherProviderInterface::class, WeatherApiWeatherProvider::class);
        $this->app->bind(SkyMonitorsProcessor::class);
        $this->app->bind(WeatherApiConnector::class, function () {
            return new WeatherApiConnector(config('services.weatherapi.api_key'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
