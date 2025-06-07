<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use function Pest\Laravel\actingAs;

test('email verification screen can be rendered', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/verify-email');

    $response->assertStatus(200);
});

test('email can be verified', function () {
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('home', absolute: false).'?verified=1');
});

test('email is not verified with invalid hash', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('redirects to vacancies.manager if email already verified', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($user);

    $response = $this->post(route('verification.send'));

    $response->assertRedirect(route('home'));
});

test('sends verification notification if email is not verified', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    actingAs($user);

    $response = $this->post(route('verification.send'));

    Notification::assertSentTo($user, VerifyEmail::class);

    $response->assertRedirect();
    $response->assertSessionHas('status', 'verification-link-sent');
});

test('redirects to vacancies.manager if user has verified email', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($user);

    $response = $this->get(route('verification.notice'));

    $response->assertRedirect(route('home'));
});

test('redirects if email is already verified', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($user);

    $mockRequest = $this->mock(EmailVerificationRequest::class, function ($mock) use ($user) {
        $mock->shouldReceive('user')->andReturn($user);
    });

    $controller = new \App\Http\Controllers\Auth\VerifyEmailController();

    $response = $controller($mockRequest);

    expect(in_array(parse_url($response->getTargetUrl(), PHP_URL_PATH), ['', '/']))
        ->and(parse_url($response->getTargetUrl(), PHP_URL_QUERY))->toBe('verified=1');

});
