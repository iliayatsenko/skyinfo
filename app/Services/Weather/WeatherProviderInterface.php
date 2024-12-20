<?php

declare(strict_types=1);

namespace App\Services\Weather;

interface WeatherProviderInterface
{
    /**
     * @throws WeatherProviderException
     */
    public function getWeather(string $city): WeatherDto;
}
