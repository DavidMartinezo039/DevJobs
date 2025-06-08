<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'user']);
    Role::create(['name' => 'admin']);
});

it('changes the role of an existing user', function () {
    $user = User::factory()->create(['email' => 'john@example.com']);
    $user->assignRole('user');

    $this->artisan('change:user-role', ['email' => 'john@example.com'])
        ->expectsOutput("El usuario tiene el rol actual: 'user'.")
        ->expectsOutputToContain('Roles disponibles para asignar:')
        ->expectsQuestion('Selecciona el número del nuevo rol', 0)
        ->expectsOutput("El rol del usuario '{$user->name}' ha sido cambiado a 'admin'.")
        ->assertExitCode(0);

    expect($user->fresh()->hasRole('admin'))->toBeTrue()
        ->and($user->fresh()->hasRole('user'))->toBeFalse();
});

it('suggests similar emails if the given one does not exist', function () {
    $similarUser = User::factory()->create(['email' => 'god@example.com']);
    $similarUser->assignRole('admin');

    $this->artisan('change:user-role')
        ->expectsQuestion('Introduce el correo electrónico del usuario', 'got@example.com')
        ->expectsOutput("No se encontró usuario con el correo 'got@example.com'.")
        ->expectsOutput('¿Quizás quisiste decir uno de estos emails?')
        ->expectsOutput(" - god@example.com")
        ->expectsQuestion('Introduce el correo electrónico del usuario', 'god@example.com')
        ->expectsOutput("El usuario tiene el rol actual: 'admin'.")
        ->expectsOutputToContain('Roles disponibles para asignar:')
        ->expectsQuestion('Selecciona el número del nuevo rol', 0)
        ->expectsOutput("El rol del usuario '{$similarUser->name}' ha sido cambiado a 'developer'.")
        ->assertExitCode(0);

    expect($similarUser->fresh()->hasRole('developer'))->toBeTrue();
});

