<?php

namespace App\Jobs;

namespace App\Jobs;

use App\Models\Gender;
use App\Models\User;
use App\Notifications\GenderChangedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyMarketingUsersOfGenderChange implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Gender $gender;
    public string $action; // 'created', 'updated', 'deleted'

    public function __construct(Gender $gender, string $action)
    {
        $this->gender = $gender;
        $this->action = $action;
    }

    public function handle(): void
    {
        $users = User::query()
            ->where('wants_marketing', true)
            ->orWhereHas('roles', fn($q) => $q->where('name', 'moderator'))
            ->get();

        foreach ($users as $user) {
            $user->notify(new GenderChangedNotification($this->gender, $this->action));
        }
    }
}
