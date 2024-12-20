<?php

declare(strict_types=1);

namespace App\Services\Weather;

use App\Http\Integrations\WeatherApi\Requests\WeatherRequest;
use App\Http\Integrations\WeatherApi\WeatherApiConnector;
use Throwable;

class WeatherApiWeatherProvider implements WeatherProviderInterface
{
    public function __construct(
        private readonly WeatherApiConnector $weatherApi,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function getWeather(string $city): WeatherDto
    {
        try {
            /** @var \App\Http\Integrations\WeatherApi\Dto\WeatherDto $weatherApiWeatherDto */
            $weatherApiWeatherDto = $this->weatherApi->send(new WeatherRequest($city))->dto();

            return new WeatherDto(
                city: $city,
                precipitationMm: $weatherApiWeatherDto->precipMm,
                uvIndex: $weatherApiWeatherDto->uv,
            );
        } catch (Throwable $e) {
            throw new WeatherProviderException('Weather provider error', 0, $e);
        }
    }
}
