<?php

namespace App\Http\Integrations\WeatherApi\Dto;

readonly class WeatherDto
{
    public function __construct(
        public float $precipMm,
        public float $uv,
    )
    {
    }
}
