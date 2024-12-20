<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Mail;

class EmailNotification implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    public function __construct(
        private string $email,
        private string $message,
    )
    {

    }

    public function handle(): void
    {
        Mail::to($this->email)->send(
            new class($this->message) extends Mailable {
                public function __construct(
                    private readonly string $message,
                )
                {

                }

                public function envelope(): Envelope
                {
                    return new Envelope(
                        from: 'noreply@skymonitor.com',
                        subject: 'Skymonitor Notification',
                    );
                }

                public function content(): Content
                {
                    return new Content(
                        text: $this->message,
                    );
                }
            }
        );
    }
}
