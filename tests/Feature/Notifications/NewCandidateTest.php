<?php

use App\Notifications\NewCandidate;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;

it('returns the correct channels', function () {
    $notification = new NewCandidate(1, 'Backend Developer', 5);

    $notifiable = new class {
        use \Illuminate\Notifications\Notifiable;
    };

    expect($notification->via($notifiable))->toBe(['mail', 'database']);
});

it('returns correct mail representation', function () {
    $notification = new NewCandidate(1, 'Backend Developer', 5);

    $notifiable = new AnonymousNotifiable();

    $mail = $notification->toMail($notifiable);

    expect($mail)->toBeInstanceOf(MailMessage::class)
        ->and($mail->introLines)->toContain(
            __('You have received a new candidate for your vacancy.'),
            __('The vacancy is: :name', ['name' => 'Backend Developer'])
        )
        ->and($mail->actionText)->toBe(__('See notification'))
        ->and($mail->actionUrl)->toBe(url('/notifications'))
        ->and($mail->outroLines)->toContain(__('Thank you for using DevJobs.'));
});

it('returns correct database payload', function () {
    $notification = new NewCandidate(7, 'Fullstack Developer', 2);

    $notifiable = new AnonymousNotifiable();

    expect($notification->toDatabase($notifiable))->toBe([
        'vacancy_id' => 7,
        'vacancy_title' => 'Fullstack Developer',
        'candidate_id' => 2,
    ]);
});
