<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PhoneNotification implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    public function __construct(
        private readonly string $phone,
        private readonly string $message,
    )
    {
    }

    public function handle(): void
    {
        // TODO: Send SMS to $this->phone with $this->message

    }
}
