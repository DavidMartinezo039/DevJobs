<?php

use App\Jobs\SendCandidateStatusEmailJob;
use App\Livewire\Candidates;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

uses()->group('livewire', 'candidates')->beforeEach(function () {
})->in(__DIR__);

it('mounts and sets statuses and originalStatuses', function () {
    $vacancy = Vacancy::factory()->create();
    $user = User::factory()->create();

    $vacancy->users()->attach($user->id, [
        'status' => 'pending',
        'cv' => 'some_value',
    ]);

    Livewire::test(Candidates::class, ['vacancy' => $vacancy])
        ->assertSet('statuses.' . $user->id, 'pending')
        ->assertSet('originalStatuses.' . $user->id, 'pending');
});

it('setStatus updates status property', function () {
    $vacancy = Vacancy::factory()->create();
    $user = User::factory()->create();

    $vacancy->users()->attach($user->id, [
        'status' => 'pending',
        'cv' => 'some_value',
    ]);
    Livewire::test(Candidates::class, ['vacancy' => $vacancy])
        ->call('setStatus', $user->id, 'accepted')
        ->assertSet('statuses.' . $user->id, 'accepted');
});

it('saveStatuses updates pivot, dispatches jobs and flashes message', function () {
    Queue::fake();

    $vacancy = Vacancy::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $vacancy->users()->attach($user1->id, [
        'status' => 'pending',
        'cv' => 'some_value',
    ]);
    $vacancy->users()->attach($user2->id, [
        'status' => 'pending',
        'cv' => 'some_value',
    ]);
    $component = Livewire::test(Candidates::class, ['vacancy' => $vacancy]);

    $component->call('setStatus', $user1->id, 'accepted');
    $component->call('setStatus', $user2->id, 'rejected');

    $component->call('saveStatuses')
        ->assertOk();

    expect(\DB::table('candidates')->where('vacancy_id', $vacancy->id)->where('user_id', $user1->id)->value('status'))
        ->toBe('accepted')
        ->and(\DB::table('candidates')->where('vacancy_id', $vacancy->id)->where('user_id', $user2->id)->value('status'))
        ->toBe('rejected');


    Queue::assertPushed(SendCandidateStatusEmailJob::class, fn ($job) =>
        $job->user->is($user1) && $job->status === 'accepted');

    Queue::assertPushed(SendCandidateStatusEmailJob::class, fn ($job) =>
        $job->user->is($user2) && $job->status === 'rejected');
});

it('does not dispatch job if status is not accepted or rejected', function () {
    Queue::fake();

    $vacancy = Vacancy::factory()->create();
    $user = User::factory()->create();

    $vacancy->users()->attach($user->id, [
        'status' => 'pending',
        'cv' => 'some_value',
    ]);
    $component = Livewire::test(Candidates::class, ['vacancy' => $vacancy]);

    $component->call('setStatus', $user->id, 'pending');
    $component->call('saveStatuses');

    Queue::assertNotPushed(SendCandidateStatusEmailJob::class);
});
