<?php

use App\Events\UserRegistered;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

it('dispara el evento UserRegistered al crear un usuario', function () {
    Event::fake();

    $user = User::factory()->create();

    event(new UserRegistered($user));

    Event::assertDispatched(UserRegistered::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });
});

it('envía un correo de bienvenida al escuchar el evento UserRegistered', function () {
    Mail::fake();

    $user = User::factory()->create();

    event(new UserRegistered($user));

    Mail::assertQueued(WelcomeMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('contiene el nombre del usuario en el correo de bienvenida', function () {
    $user = User::factory()->make(['name' => 'Ash Ketchum']);

    $mail = new WelcomeMail($user);

    $mail->assertSeeInHtml("Hello {$user->name}");
});
