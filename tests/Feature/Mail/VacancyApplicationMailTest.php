<?php

use App\Mail\VacancyApplicationMail;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Mail;

it('can create vacancy application mail with correct data', function () {
    $vacancy = Vacancy::factory()->make(['title' => 'Backend Developer']);

    $mail = new VacancyApplicationMail($vacancy);

    expect($mail->vacancy)->toBe($vacancy);

    $envelope = $mail->envelope();
    expect($envelope->subject)->toBe(__('Confirmation of application: ') . $vacancy->title);

    $content = $mail->content();
    expect($content->markdown)->toBe('emails.vacancy.application')
        ->and($mail->attachments())->toBe([]);

});

it('sends the vacancy application mail', function () {
    Mail::fake();

    $vacancy = Vacancy::factory()->create();

    Mail::to('test@example.com')->send(new VacancyApplicationMail($vacancy));

    Mail::assertSent(VacancyApplicationMail::class, function ($mail) use ($vacancy) {
        return $mail->vacancy->is($vacancy);
    });
});
