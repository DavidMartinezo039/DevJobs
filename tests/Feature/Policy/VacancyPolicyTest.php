<?php

use App\Livewire\AppliedJobs;
use App\Livewire\ApplyVacancy;
use App\Livewire\VacanciesManager;
use App\Models\User;
use App\Models\Vacancy;
use function Pest\Laravel\actingAs;

test('view vacancy', function () {
    $user = User::factory()->create();
    $user->assignRole('recruiter');

    $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->call('show', $vacancy)
        ->assertOk();

    $user = User::factory()->create();
    $user->assignRole('god');

    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->call('show', $vacancy)
        ->assertOk();

    $user = User::factory()->create();
    $user->assignRole('moderator');

    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->call('show', $vacancy)
        ->assertOk();
});

test('create vacancy', function () {
    $user = User::factory()->create();
    $user->assignRole('recruiter');

    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->call('create')
        ->assertOk();
});

test('update vacancy', function () {
    $user = User::factory()->create();
    $user->assignRole('recruiter');

    $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->call('edit', $vacancy)
        ->assertOk();

    $user = User::factory()->create();
    $user->assignRole('god');

    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->call('edit', $vacancy)
        ->assertOk();

    $user = User::factory()->create();
    $user->assignRole('moderator');

    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->call('edit', $vacancy)
        ->assertOk();
});

test('delete vacancy', function () {
    $user = User::factory()->create();
    $user->assignRole('recruiter');

    $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);
    $vacancy2 = Vacancy::factory()->create(['user_id' => $user->id]);
    $vacancy3 = Vacancy::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->call('deleteVacancy', $vacancy)
        ->assertOk();

    $user = User::factory()->create();
    $user->assignRole('god');

    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->call('deleteVacancy', $vacancy2)
        ->assertOk();

    $user = User::factory()->create();
    $user->assignRole('moderator');

    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->call('deleteVacancy', $vacancy3)
        ->assertOk();
});

test('view pivot vacancy, my applications', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');

    actingAs($user);

    Livewire::test(AppliedJobs::class)
        ->assertOk();
});

test('delete pivot vacancy, application', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    $vacancy->users()->attach(auth()->id(), ['cv' => 'cv_test.pdf']);

    Livewire::test(ApplyVacancy::class, ['vacancy' => $vacancy])
        ->call('removeCv')
        ->assertOk();

    $user = User::factory()->create();
    $user->assignRole('developer');

    $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);
    $vacancy3 = Vacancy::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    $vacancy->users()->attach(auth()->id(), ['cv' => 'cv_test.pdf']);
    $vacancy3->users()->attach(auth()->id(), ['cv' => 'cv_test.pdf']);

    Livewire::test(ApplyVacancy::class, ['vacancy' => $vacancy])
        ->call('removeCv')
        ->assertOk();

    $user = User::factory()->create();
    $user->assignRole('moderator');

    $vacancy2 = Vacancy::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    $vacancy2->users()->attach(auth()->id(), ['cv' => 'cv_test.pdf']);

    Livewire::test(ApplyVacancy::class, ['vacancy' => $vacancy2])
        ->call('removeCv')
        ->assertOk();
    Livewire::test(ApplyVacancy::class, ['vacancy' => $vacancy3])
        ->call('removeCv')
        ->assertOk();
});

test('view pivot vacancy, application', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    $vacancy1 = Vacancy::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    $vacancy1->users()->attach(auth()->id(), ['cv' => 'cv_test.pdf']);

    Livewire::test(ApplyVacancy::class, ['vacancy' => $vacancy1])
        ->assertOk();
});

test('create pivot vacancy', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');

    $vacancy = Vacancy::factory()->create(['user_id' => $user->id]);

    actingAs($user);

    Livewire::test(ApplyVacancy::class, ['vacancy' => $vacancy])
        ->call('applyVacancy')
        ->assertOk();
});
