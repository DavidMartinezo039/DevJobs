<?php

namespace App\Jobs;

use App\Mail\CandidateAcceptedMail;
use App\Mail\CandidateRejectedMail;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCandidateStatusEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $vacancy;
    public $user;
    public $status;

    public function __construct(Vacancy $vacancy, User $user, string $status)
    {
        $this->vacancy = $vacancy;
        $this->user = $user;
        $this->status = $status;
    }

    public function handle(): void
    {
        if ($this->status === 'accepted') {
            Mail::to($this->user->email)->send(
                new CandidateAcceptedMail($this->vacancy, $this->user)
            );
        } elseif ($this->status === 'rejected') {
            Mail::to($this->user->email)->send(
                new CandidateRejectedMail($this->vacancy, $this->user)
            );
        }
    }
}
