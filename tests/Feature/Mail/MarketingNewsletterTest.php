<?php

use App\Mail\MarketingNewsletter;
use Illuminate\Support\Facades\Mail;

it('creates marketing newsletter mail with username', function () {
    $username = 'Alice';
    $mail = new MarketingNewsletter($username);

    expect($mail->username)->toBe('Alice');

    $envelope = $mail->envelope();
    expect($envelope->subject)->toBe(__('Marketing Newsletter'));

    $content = $mail->content();
    expect($content->markdown)->toBe('mail.marketing-newsletter')
        ->and($mail->attachments())->toBe([]);

});

it('sends the marketing newsletter mail', function () {
    Mail::fake();

    Mail::to('alice@example.com')->send(new MarketingNewsletter('Alice'));

    Mail::assertSent(MarketingNewsletter::class, function ($mail) {
        return $mail->username === 'Alice';
    });
});
