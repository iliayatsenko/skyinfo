<?php

namespace App\Http\Integrations\WeatherApi\Requests;

use App\Http\Integrations\WeatherApi\Dto\WeatherDto;
use Illuminate\Support\Facades\Cache;
use RuntimeException;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class WeatherRequest extends Request implements Cacheable
{
    use HasCaching;

    public function __construct(
        private readonly string $cityName
    )
    {

    }

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/current.json';
    }

    public function defaultQuery(): array
    {
        return [
            'q' => $this->cityName,
            'aqi' => 'no', // do not include air quality info
        ];
    }

    public function createDtoFromResponse(Response $response): WeatherDto
    {
        if ($response->failed()) {
            throw new RuntimeException(
                sprintf('Weatherapi response error: code %d, message %s', $response->status(), $response->body())
            );
        }

        $json = $response->json();

        return new WeatherDto(
            $json['current']['precip_mm'] ?? throw new RuntimeException('Precipitation data not found in weatherapi response'),
            $json['current']['uv'] ?? throw new RuntimeException('UV data not found in weatherapi response'),
        );
    }

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver(Cache::store('redis'));
    }

    public function cacheExpiryInSeconds(): int
    {
        return 3600; // one hour
    }
}
