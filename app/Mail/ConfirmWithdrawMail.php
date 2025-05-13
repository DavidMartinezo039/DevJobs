<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmWithdrawMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vacancy;
    public $url;

    public function __construct($vacancy, $url)
    {
        $this->vacancy = $vacancy;
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject(__('Confirmation of CV deletion'))
            ->markdown('emails.confirm-withdraw');
    }

}
