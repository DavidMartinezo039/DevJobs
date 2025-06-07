<?php

use App\Livewire\Dashboard;
use App\Models\User;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Artisan;

test('usuario con rol god puede ver el dashboard (Livewire)', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeLivewire('dashboard');
});

test('usuario con rol god puede ejecutar el backup desde el componente livewire', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    Artisan::spy();

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->call('backup');

    Artisan::shouldHaveReceived('call')->with('backup:database');
});
