<?php

use App\Livewire\CvManager;
use App\Models\PersonalData;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('view god and moderator', function () {
    $user = createUserWithCompleteCv('god');

    actingAs($user);

    $selectedCv = $user->cvs()->first();

    Livewire::test(CvManager::class)
        ->call('show', $selectedCv)
        ->assertOk();

    $user = createUserWithCompleteCv('moderator');

    actingAs($user);

    $selectedCv = $user->cvs()->first();

    Livewire::test(CvManager::class)
        ->call('show', $selectedCv)
        ->assertOk();
});

test('update CV', function () {
    $user = createUserWithCompleteCv('developer');

    actingAs($user);

    $selectedCv = $user->cvs()->first();

    Livewire::test(CvManager::class)
        ->call('edit', $selectedCv)
        ->assertOk();

    $user1 = User::factory()->create();
    $user1->assignRole('god');

    actingAs($user1);

    $selectedCv = $user->cvs()->first();

    Livewire::test(CvManager::class)
        ->call('edit', $selectedCv)
        ->assertOk();

    $user2 = User::factory()->create();
    $user2->assignRole('moderator');

    actingAs($user2);

    $selectedCv = $user->cvs()->first();

    Livewire::test(CvManager::class)
        ->call('edit', $selectedCv)
        ->assertOk();
});

test('delete CV', function () {
    $user = createUserWithCompleteCv('developer');

    actingAs($user);

    $selectedCv = $user->cvs()->first();

    Livewire::test(CvManager::class)
        ->call('delete', $selectedCv)
        ->assertOk();

    $user = createUserWithCompleteCv('developer');
    $user1 = User::factory()->create();
    $user1->assignRole('god');

    actingAs($user1);

    $selectedCv = $user->cvs()->first();

    Livewire::test(CvManager::class)
        ->call('delete', $selectedCv)
        ->assertOk();

    $user = createUserWithCompleteCv('developer');
    $user2 = User::factory()->create();
    $user2->assignRole('moderator');
    actingAs($user2);

    $selectedCv = $user->cvs()->first();

    Livewire::test(CvManager::class)
        ->call('delete', $selectedCv)
        ->assertOk();
});
