<?php

declare(strict_types=1);

namespace App\Services\Weather;

readonly class WeatherDto
{
    public function __construct(
        public string $city,
        public float $precipitationMm,
        public float $uvIndex,
    ) {}
}
