<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Vacancy $vacancy;

    /**
     * Create a new message instance.
     */
    public function __construct(Vacancy $vacancy, User $user)
    {
        $this->user = $user;
        $this->vacancy = $vacancy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('We appreciate your interest') . ' – {{ $vacancy->title }}',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.candidate-rejected',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
