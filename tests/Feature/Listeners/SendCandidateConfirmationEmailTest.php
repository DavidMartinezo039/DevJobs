<?php

use App\Events\VacancyApplied;
use App\Listeners\SendCandidateConfirmationEmail;
use App\Mail\VacancyApplicationMail;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Mail;

it('sends a confirmation email to the candidate', function () {
    Mail::fake();

    $user = User::factory()->make(['email' => 'test@example.com']);
    $vacancy = Vacancy::factory()->make();

    $event = new VacancyApplied($vacancy, $user);

    $listener = new SendCandidateConfirmationEmail();
    $listener->handle($event);

    Mail::assertSent(VacancyApplicationMail::class, function ($mail) use ($vacancy) {
        return $mail->vacancy->title === $vacancy->title;
    });

    Mail::assertSent(VacancyApplicationMail::class, 1);
});
