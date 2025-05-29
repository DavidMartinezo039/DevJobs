<?php

use App\Jobs\SendCandidateStatusEmailJob;
use App\Mail\CandidateAcceptedMail;
use App\Mail\CandidateRejectedMail;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();

    $this->vacancy = Vacancy::factory()->create();
    $this->user = User::factory()->create(['email' => 'candidate@example.com']);
});

it('sends accepted email when status is accepted', function () {
    $job = new SendCandidateStatusEmailJob($this->vacancy, $this->user, 'accepted');
    $job->handle();

    Mail::assertSent(CandidateAcceptedMail::class, function ($mail) {
        return $mail->hasTo('candidate@example.com');
    });

    Mail::assertNotSent(CandidateRejectedMail::class);
});

it('sends rejected email when status is rejected', function () {
    $job = new SendCandidateStatusEmailJob($this->vacancy, $this->user, 'rejected');
    $job->handle();

    Mail::assertSent(CandidateRejectedMail::class, function ($mail) {
        return $mail->hasTo('candidate@example.com');
    });

    Mail::assertNotSent(CandidateAcceptedMail::class);
});

it('does not send any email for other status', function () {
    $job = new SendCandidateStatusEmailJob($this->vacancy, $this->user, 'pending');
    $job->handle();

    Mail::assertNothingSent();
});
