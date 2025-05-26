<?php

use App\Livewire\ConfirmWithdraw;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use App\Events\CandidateWithdrew;
use App\Models\User;
use App\Models\Vacancy;
use function Pest\Laravel\actingAs;
use Livewire\Livewire;

test('withdraw dispatches CandidateWithdrew event and deletes cv', function () {
    Event::fake();
    Storage::fake('public');

    $user = User::factory()->create()->assignRole('developer');
    $vacancy = Vacancy::factory()->create();

    $vacancy->users()->attach($user->id, ['cv' => 'testcv.pdf']);

    actingAs($user);

    Livewire::test(ConfirmWithdraw::class, ['vacancy' => $vacancy])
        ->call('withdraw')
        ->assertRedirect(route('home'));


    Storage::disk('public')->assertMissing('cv/testcv.pdf');

    Event::assertDispatched(CandidateWithdrew::class, function ($event) use ($vacancy, $user) {
        return $event->vacancy->id === $vacancy->id && $event->user->id === $user->id;
    });

    $this->assertFalse($vacancy->users()->where('user_id', $user->id)->exists());
});
