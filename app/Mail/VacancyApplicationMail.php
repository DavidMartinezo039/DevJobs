<?php

namespace App\Mail;

use App\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VacancyApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vacancy;

    public function __construct(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Confirmation of application: ') . $this->vacancy->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.vacancy.application',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
