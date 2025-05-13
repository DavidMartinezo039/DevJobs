<?php

namespace App\Events;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CandidateWithdrew
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $vacancy;
    public $user;

    public function __construct(Vacancy $vacancy, User $user)
    {
        $this->vacancy = $vacancy;
        $this->user = $user;
    }
}
