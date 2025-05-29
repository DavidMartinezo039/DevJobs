<?php

use App\Mail\NotifyRecruiterWithdraw;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Mail;

it('can create notify recruiter withdraw mail with correct data', function () {
    $vacancy = Vacancy::factory()->make(['title' => 'Frontend Developer']);
    $candidate = User::factory()->make(['name' => 'John Doe']);

    $mail = new NotifyRecruiterWithdraw($vacancy, $candidate);

    expect($mail->vacancy)->toBe($vacancy)
        ->and($mail->candidate)->toBe($candidate);

    $envelope = $mail->envelope();
    expect($envelope->subject)->toBe(__('A candidate has withdrawn from your vacancy'));

    $content = $mail->content();
    expect($content->markdown)->toBe('emails.recruiter-notification')
        ->and($mail->attachments())->toBe([]);

});

it('sends the notify recruiter withdraw mail', function () {
    Mail::fake();

    $vacancy = Vacancy::factory()->create();
    $candidate = User::factory()->create();

    Mail::to('recruiter@example.com')->send(new NotifyRecruiterWithdraw($vacancy, $candidate));

    Mail::assertSent(NotifyRecruiterWithdraw::class, function ($mail) use ($vacancy, $candidate) {
        return $mail->vacancy->is($vacancy) && $mail->candidate->is($candidate);
    });
});
