<?php

use App\Mail\CandidateRejectedMail;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Mail;

it('can create candidate rejected mail with correct data', function () {
    $user = User::factory()->make();
    $vacancy = Vacancy::factory()->make(['title' => 'Frontend Developer']);

    $mail = new CandidateRejectedMail($vacancy, $user);

    expect($mail->user)->toBe($user)
        ->and($mail->vacancy)->toBe($vacancy);

    $envelope = $mail->envelope();
    expect($envelope->subject)->toContain(__('We appreciate your interest'));

    $content = $mail->content();
    expect($content->markdown)->toBe('emails.candidate-rejected')
        ->and($mail->attachments())->toBeEmpty();

});

it('sends the candidate rejected mail', function () {
    Mail::fake();

    $user = User::factory()->create();
    $vacancy = Vacancy::factory()->create();

    Mail::send(new CandidateRejectedMail($vacancy, $user));

    Mail::assertSent(CandidateRejectedMail::class, function ($mail) use ($user, $vacancy) {
        return $mail->user->is($user) && $mail->vacancy->is($vacancy);
    });
});
