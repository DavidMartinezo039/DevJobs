<?php

use App\Mail\CandidateWithdrawMail;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Mail;

it('creates the candidate withdraw mail', function () {
    $vacancy = new Vacancy(['title' => 'Frontend Developer']);
    $mail = new CandidateWithdrawMail($vacancy);

    expect($mail->vacancy->title)->toBe('Frontend Developer');

    $envelope = $mail->envelope();
    expect($envelope->subject)->toBe(__('You have withdrawn your candidacy'));

    $content = $mail->content();
    expect($content->markdown)->toBe('emails.candidate-withdraw')
        ->and($mail->attachments())->toBe([]);

});

it('sends the candidate withdraw mail', function () {
    Mail::fake();

    $vacancy = Vacancy::factory()->make();

    Mail::to('user@example.com')->send(new CandidateWithdrawMail($vacancy));

    Mail::assertSent(CandidateWithdrawMail::class, function ($mail) use ($vacancy) {
        return $mail->vacancy->title === $vacancy->title;
    });
});
