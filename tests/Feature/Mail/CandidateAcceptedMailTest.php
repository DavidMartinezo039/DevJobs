<?php

use App\Mail\CandidateAcceptedMail;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Mail;

it('can create candidate accepted mail with correct data', function () {
    $user = User::factory()->make();
    $vacancy = Vacancy::factory()->make();

    $mail = new CandidateAcceptedMail($vacancy, $user);

    expect($mail->user)->toBe($user)
        ->and($mail->vacancy)->toBe($vacancy);

    $envelope = $mail->envelope();
    expect($envelope->subject)->toContain('🎉');

    $content = $mail->content();
    expect($content->markdown)->toBe('emails.candidate-accepted')
        ->and($mail->attachments())->toBeEmpty();

});

it('sends the candidate accepted mail', function () {
    Mail::fake();

    $user = User::factory()->create();
    $vacancy = Vacancy::factory()->create();

    Mail::send(new CandidateAcceptedMail($vacancy, $user));

    Mail::assertSent(CandidateAcceptedMail::class, function ($mail) use ($user, $vacancy) {
        return $mail->user->id === $user->id && $mail->vacancy->id === $vacancy->id;
    });
});
