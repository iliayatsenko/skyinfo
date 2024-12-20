<?php

use App\Http\Integrations\WeatherApi\Requests\WeatherRequest;
use App\Models\Skymonitor;
use App\Notifications\SkymonitorAlert;
use App\Services\SkymonitorsProcessor;
use Illuminate\Support\Facades\Notification;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

it ('processes all skymonitors', function () {
    Notification::fake();

    Skymonitor::factory()->count(3)->create();

    Saloon::fake([
        WeatherRequest::class => MockResponse::fixture('weatherapi_current_response.json'),
    ]);

    $skymonitorsProcessor = $this->spy(SkymonitorsProcessor::class);

    $this->artisan('app:process-skymonitors')
        ->assertExitCode(0);

    $skymonitorsProcessor
        ->shouldHaveReceived('process')
        ->times(3);
});

it ('notifies about weather provider exception', function () {
    Notification::fake();

    Skymonitor::factory()->create();

    Saloon::fake([
        WeatherRequest::class => MockResponse::make('Error!', 500),
    ]);

    $this->artisan('app:process-skymonitors')
        ->assertExitCode(0);

    Notification::assertSentOnDemand(SkymonitorAlert::class);
});
