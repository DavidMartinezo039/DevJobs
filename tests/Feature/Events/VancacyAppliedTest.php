<?php

use Illuminate\Support\Facades\Event;
use App\Events\VacancyApplied;
use App\Models\User;
use App\Models\Vacancy;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

test('applyVacancy dispatches VacancyApplied event', function () {
    Event::fake();

    $user = User::factory()->create()->assignRole('developer');
    $vacancy = Vacancy::factory()->create();

    actingAs($user);

    Livewire::test(\App\Livewire\ApplyVacancy::class, ['vacancy' => $vacancy])
        ->set('cv', \Illuminate\Http\UploadedFile::fake()->create('cv.pdf', 100))
        ->call('applyVacancy');

    Event::assertDispatched(VacancyApplied::class, function ($event) use ($vacancy, $user) {
        return $event->vacancy->id === $vacancy->id && $event->user->id === $user->id;
    });
});
