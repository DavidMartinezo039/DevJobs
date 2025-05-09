<?php

namespace App\Listeners;

use App\Events\VacancyApplied;
use App\Notifications\NewCandidate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCandidateNotification
{
    public function handle(VacancyApplied $event): void
    {
        $event->vacancy->recruiter->notify(
            new NewCandidate($event->vacancy->id, $event->vacancy->title, $event->user->id)
        );
    }
}
