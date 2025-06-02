<?php

namespace App\Listeners;

use App\Events\NewVacancyCreated;
use App\Mail\MatchingVacancyNotification;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Mail;

class NotifyUsersWithMatchingPreferences
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewVacancyCreated $event)
    {
        $vacancy = $event->vacancy;

        $matchingPreferences = UserPreference::with('user')
            ->matchingVacancy($vacancy)
            ->where('user_id', '!=', $vacancy->user_id)
            ->get();

        $matchingUsers = $matchingPreferences->pluck('user')->unique();

        foreach ($matchingUsers as $user) {
            Mail::to($user->email)->queue(new MatchingVacancyNotification($vacancy, $user));
        }
    }
}
