<?php

use App\Models\User;

use function Pest\Laravel\artisan;

it('muestra el token usando --email y --password válidos', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('secret123'),
    ]);

    artisan('user:show-token', [
        '--email' => 'test@example.com',
        '--password' => 'secret123',
    ])
        ->expectsOutputToContain('')
        ->assertExitCode(0);

    expect($user->tokens()->count())->toBe(1);
});

it('falla si el email no existe', function () {
    artisan('user:show-token', [
        '--email' => 'nope@example.com',
        '--password' => 'anything',
    ])
        ->expectsOutputToContain('Usuario con email nope@example.com no encontrado.')
        ->assertExitCode(1);
});

it('falla si la contraseña es incorrecta', function () {
    $user = User::factory()->create([
        'email' => 'real@example.com',
        'password' => bcrypt('correct'),
    ]);

    artisan('user:show-token', [
        '--email' => 'real@example.com',
        '--password' => 'wrong',
    ])
        ->expectsOutputToContain('Contraseña incorrecta.')
        ->assertExitCode(1);
});

it('falla si no se proporciona ni id ni email/password', function () {
    artisan('user:show-token')
        ->expectsOutputToContain('Debe proporcionar email y contraseña si no se usa --id.')
        ->assertExitCode(1);
});

it('falla si el ID no existe', function () {
    artisan('user:show-token', [
        '--id' => 9999,
    ])
        ->expectsOutputToContain('Usuario con ID 9999 no encontrado.')
        ->assertExitCode(1);
});
