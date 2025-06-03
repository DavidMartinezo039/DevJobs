<?php

use App\Jobs\SendWelcomeEmail;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

it('sends a welcome email to the user when the job is dispatched', function () {
    Mail::fake();

    $user = User::factory()->create();

    (new SendWelcomeEmail($user))->handle();

    Mail::assertSent(WelcomeMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('dispatches the SendWelcomeEmail job', function () {
    Queue::fake();

    $user = User::factory()->create();

    SendWelcomeEmail::dispatch($user);

    Queue::assertPushed(SendWelcomeEmail::class, function ($job) use ($user) {
        return $job->user->is($user);
    });
});
