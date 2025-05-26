<?php

use App\Livewire\Admin\GendersManager;
use App\Models\Gender;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('update Gender', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    actingAs($user);

    $gender = Gender::factory()->create();

    Livewire::test(GendersManager::class)
        ->call('edit', $gender)
        ->assertOk();

    $gender = Gender::factory()->create([
        'is_default' => true
    ]);

    Livewire::test(GendersManager::class)
        ->call('edit', $gender)
        ->assertOk();

    $user = User::factory()->create();
    $user->assignRole('moderator');

    actingAs($user);

    $gender = Gender::factory()->create();

    Livewire::test(GendersManager::class)
        ->call('edit', $gender)
        ->assertOk();
});

test('delete Gender', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    actingAs($user);

    $gender = Gender::factory()->create();

    Livewire::test(GendersManager::class)
        ->call('deleteGender', $gender)
        ->assertOk();

    $gender = Gender::factory()->create([
        'is_default' => true
    ]);

    Livewire::test(GendersManager::class)
        ->call('deleteGender', $gender)
        ->assertOk();

    $user = User::factory()->create();
    $user->assignRole('moderator');

    actingAs($user);

    $gender = Gender::factory()->create();

    Livewire::test(GendersManager::class)
        ->call('deleteGender', $gender)
        ->assertOk();
});

test('Cambiar a default Gender', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    actingAs($user);

    $gender = Gender::factory()->create();

    Livewire::test(GendersManager::class)
        ->call('saveChanges')
        ->assertOk();
});
