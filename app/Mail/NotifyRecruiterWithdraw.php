<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyRecruiterWithdraw extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Vacancy $vacancy, public User $candidate)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('A candidate has withdrawn from your vacancy')
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.recruiter-notification'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
