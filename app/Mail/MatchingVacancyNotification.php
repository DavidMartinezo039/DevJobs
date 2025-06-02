<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MatchingVacancyNotification extends Mailable implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public Vacancy $vacancy;
    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Vacancy $vacancy, User $user)
    {
        $this->vacancy = $vacancy;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('New vacancy that matches your preferences'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.matching-vacancy',
        );
    }
}
