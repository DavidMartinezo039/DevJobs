<?php

use App\Livewire\VacanciesManager;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('renders forms components', function () {
    $user = User::factory()->create()->assignRole('recruiter');
    actingAs($user);

    Livewire::test(VacanciesManager::class)
        ->set('view', 'create')
        ->assertOk();
});
