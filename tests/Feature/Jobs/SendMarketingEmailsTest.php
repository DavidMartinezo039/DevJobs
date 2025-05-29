<?php

use App\Jobs\SendMarketingEmails;
use App\Mail\MarketingNewsletter;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    // Creamos un usuario que quiere marketing
    User::factory()->create([
        'wants_marketing' => true,
        'name' => 'David',
        'email' => 'david@example.com',
    ]);

    // Y uno que no quiere marketing
    User::factory()->create([
        'wants_marketing' => false,
        'name' => 'Sofia',
        'email' => 'sofia@example.com',
    ]);
});

it('queues marketing emails only for users who want marketing', function () {
    Mail::fake();

    // Ejecutamos el job manualmente (sin dispatch)
    (new SendMarketingEmails())->handle();

    // Debe haberse encolado 1 mail
    Mail::assertQueued(MarketingNewsletter::class, 1);

    // Aseguramos que el mail fue para el usuario "David"
    Mail::assertQueued(MarketingNewsletter::class, function ($mail) {
        return $mail->username === 'David';
    });

    // Verificamos que no se haya enviado mail a Sofia
    Mail::assertNotQueued(MarketingNewsletter::class, function ($mail) {
        return $mail->username === 'Sofia';
    });
});
