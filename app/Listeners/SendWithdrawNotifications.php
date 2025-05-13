<?php

namespace App\Listeners;

use App\Events\CandidateWithdrew;
use App\Mail\CandidateWithdrawMail;
use App\Mail\NotifyRecruiterWithdraw;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWithdrawNotifications
{
    public function handle(CandidateWithdrew $event)
    {
        Mail::to($event->user->email)->queue(new CandidateWithdrawMail($event->vacancy));
        Mail::to($event->vacancy->user->email)->queue(new NotifyRecruiterWithdraw($event->vacancy, $event->user));
    }

}
