<?php

namespace App\Mail;

use App\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateWithdrawMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Vacancy $vacancy)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('You have withdrawn your candidacy')
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.candidate-withdraw'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
