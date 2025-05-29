<?php

use App\Livewire\ShowVacancy;
use App\Models\Vacancy;
use Livewire\Livewire;

it('mounts the component with vacancy and renders the correct view', function () {
    $vacancy = Vacancy::factory()->create();

    Livewire::test(ShowVacancy::class, ['vacancy' => $vacancy])
        ->assertSet('vacancy', $vacancy)
        ->assertViewIs('livewire.show-vacancy');
});
