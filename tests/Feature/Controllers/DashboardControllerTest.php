<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Artisan;

test('usuario con rol admin puede ver el dashboard', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertViewIs('dashboard.index')
        ->assertViewHas('user', $user);
});

test('ejecuta el comando de backup y redirige con mensaje', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    Artisan::spy();

    actingAs($user)
        ->post(route('admin.backup'))
        ->assertRedirect()
        ->assertSessionHas('success', 'Backup de base de datos generado.');

    Artisan::shouldHaveReceived('call')->with('backup:database');
});
