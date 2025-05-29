<?php

use App\Events\CandidateWithdrew;
use App\Listeners\SendWithdrawNotifications;
use App\Mail\CandidateWithdrawMail;
use App\Mail\NotifyRecruiterWithdraw;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();

    $this->candidate = User::factory()->make([
        'email' => 'candidate@example.com',
    ]);

    $this->recruiter = User::factory()->make([
        'email' => 'recruiter@example.com',
    ]);

    $this->vacancy = Vacancy::factory()->make();
    $this->vacancy->setRelation('user', $this->recruiter);

    $this->event = new CandidateWithdrew($this->vacancy, $this->candidate);

    $this->listener = new SendWithdrawNotifications();
});

it('sends withdraw notification emails to candidate and recruiter', function () {
    $this->listener->handle($this->event);

    Mail::assertQueued(CandidateWithdrawMail::class, function ($mail) {
        return $mail->vacancy->is($this->vacancy);
    });

    Mail::assertQueued(NotifyRecruiterWithdraw::class, function ($mail) {
        return $mail->vacancy->is($this->vacancy)
            && $mail->candidate->is($this->candidate);
    });
});
