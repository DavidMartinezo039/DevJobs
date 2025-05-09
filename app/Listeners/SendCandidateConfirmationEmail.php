<?php

namespace App\Listeners;

use App\Events\VacancyApplied;
use App\Mail\VacancyApplicationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendCandidateConfirmationEmail
{
    public function handle(VacancyApplied $event): void
    {
        Mail::to($event->user->email)->send(
            new VacancyApplicationMail($event->vacancy)
        );
    }
}
