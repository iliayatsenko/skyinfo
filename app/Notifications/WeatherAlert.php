<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class WeatherAlert extends Notification implements ShouldQueue
{
    use Queueable;
    use Queueable;

    public function __construct(
        private readonly string $alertMessage
    ) {}

    public function via(object $notifiable): array
    {
        if (! ($notifiable instanceof AnonymousNotifiable)) {
            throw new \InvalidArgumentException('The notifiable must be an instance of AnonymousNotifiable');
        }

        $channels = [];

        if ($notifiable->routeNotificationFor('mail')) {
            $channels[] = 'mail';
        }

        if ($notifiable->routeNotificationFor('vonage')) {
            $channels[] = 'vonage';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from('noreply@skymonitor.com', 'Skymonitor')
            ->markdown('mail.weather-alert', ['alertMessage' => $this->alertMessage]);
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content($this->alertMessage);
    }
}
