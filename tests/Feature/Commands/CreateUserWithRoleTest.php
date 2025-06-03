<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;


it('crea un usuario con el rol especificado', function () {
    $this->artisan('make:user', [
        'name' => 'Juan',
        'email' => 'juan@example.com',
        'role' => 'god',
    ])
        ->expectsQuestion('Contraseña (mínimo 8 caracteres)', 'password123')
        ->expectsOutput("✅ Usuario 'Juan' creado con el rol 'god'.")
        ->assertExitCode(0);

    $user = User::where('email', 'juan@example.com')->first();

    expect($user)->not->toBeNull()
        ->and(Hash::check('password123', $user->password))->toBeTrue()
        ->and($user->hasRole('god'))->toBeTrue();
});

it('no permite crear usuario si el correo ya existe', function () {
    User::factory()->create([
        'email' => 'duplicado@example.com'
    ]);

    $this->artisan('make:user', [
        'name' => 'Repetido',
        'email' => 'duplicado@example.com',
        'role' => 'god',
    ])
        ->expectsQuestion('Contraseña (mínimo 8 caracteres)', 'password123')
        ->expectsOutputToContain('❌ The email has already been taken.')
        ->assertExitCode(0);
});

it('no permite rol inexistente', function () {
    $this->artisan('make:user', [
        'name' => 'Mario',
        'email' => 'mario@example.com',
        'role' => 'invalid-role',
    ])
        ->expectsQuestion('Contraseña (mínimo 8 caracteres)', 'password123')
        ->expectsOutputToContain('❌ The selected role is invalid.')
        ->assertExitCode(0);
});

it('crea un usuario eligiendo el rol por número', function () {
    $this->artisan('make:user')
        ->expectsQuestion('Nombre del usuario (ej: Juan Pérez)', 'Luis')
        ->expectsQuestion('Correo electrónico (ej: juan@example.com)', 'luis@example.com')
        ->expectsQuestion('Contraseña (mínimo 8 caracteres)', 'password123')
        ->expectsQuestion('Selecciona el número del rol', '1')
        ->expectsOutput("✅ Usuario 'Luis' creado con el rol 'god'.")
        ->assertExitCode(0);

    $user = User::where('email', 'luis@example.com')->first();

    expect($user)->not->toBeNull()
        ->and(Hash::check('password123', $user->password))->toBeTrue()
        ->and($user->hasRole('god'))->toBeTrue();
});
