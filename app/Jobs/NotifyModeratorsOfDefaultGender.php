<?php

namespace App\Jobs;

use App\Models\Gender;
use App\Models\User;
use App\Notifications\GenderDefaultStatusChangedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyModeratorsOfDefaultGender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $gender;

    public function __construct(Gender $gender)
    {
        $this->gender = $gender;
    }

    public function handle()
    {
        $moderators = User::role('moderator')->get();

        foreach ($moderators as $user) {
            $user->notify(new GenderDefaultStatusChangedNotification($this->gender));
        }
    }
}
