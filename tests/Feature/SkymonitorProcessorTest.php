<?php

use App\Models\Skymonitor;
use App\Notifications\WeatherAlert;
use App\Services\SkymonitorsProcessor;
use App\Services\Weather\WeatherDto;
use Illuminate\Support\Facades\Notification;

it ('sends notification if UV index exceeds configured threshold', function () {
    Notification::fake();

    $skymonitor = new Skymonitor([
        'precipitation_threshold' => 10,
        'uv_index_threshold' => 5,
        'email' => 'email@mail.com',
        'phone' => '123456789',
        'city' => 'London',
    ]);

    $weatherDto = new WeatherDto(
        city: 'London',
        precipitationMm: 9,
        uvIndex: 6,
    );

    $skymonitorsProcessor = new SkymonitorsProcessor();

    $skymonitorsProcessor->process($skymonitor, $weatherDto);

    Notification::assertSentOnDemand(WeatherAlert::class, function (WeatherAlert $notification, array $channels, object $notifiable) use ($skymonitor) {
        return $notifiable->routes['mail'] === $skymonitor->email
            && $notifiable->routes['vonage'] === $skymonitor->phone;

    });
});

it ('sends notification if precipitation exceeds configured threshold', function () {
    Notification::fake();

    $skymonitor = new Skymonitor([
        'precipitation_threshold' => 10,
        'uv_index_threshold' => 5,
        'email' => 'email@mail.com',
        'phone' => '123456789',
        'city' => 'London',
    ]);

    $weatherDto = new WeatherDto(
        city: 'London',
        precipitationMm: 11,
        uvIndex: 4,
    );

    $skymonitorsProcessor = new SkymonitorsProcessor();

    $skymonitorsProcessor->process($skymonitor, $weatherDto);

    Notification::assertSentOnDemand(WeatherAlert::class, function (WeatherAlert $notification, array $channels, object $notifiable) use ($skymonitor) {
        return $notifiable->routes['mail'] === $skymonitor->email
            && $notifiable->routes['vonage'] === $skymonitor->phone;

    });
});

it ('does not send notification if thresholds are not exceeded', function () {
    Notification::fake();

    $skymonitor = new Skymonitor([
        'precipitation_threshold' => 10,
        'uv_index_threshold' => 5,
        'email' => 'email@mail.com',
        'phone' => '123456789',
        'city' => 'London',
    ]);

    $weatherDto = new WeatherDto(
        city: 'London',
        precipitationMm: 9,
        uvIndex: 4,
    );

    $skymonitorsProcessor = new SkymonitorsProcessor();

    $skymonitorsProcessor->process($skymonitor, $weatherDto);

    Notification::assertNothingSent();
});




