<?php

use App\Events\VacancyApplied;
use App\Listeners\SendCandidateNotification;
use App\Models\User;
use App\Models\Vacancy;
use App\Notifications\NewCandidate;
use Illuminate\Support\Facades\Notification;

it('sends a notification to the recruiter when a candidate applies', function () {
    Notification::fake();

    $recruiter = User::factory()->make(['id' => 1]);
    $recruiter->assignRole('recruiter');
    $user = User::factory()->make(['id' => 2]);

    $vacancy = Vacancy::factory()->make([
        'id' => 10,
        'title' => 'Senior Developer',
        'recruiter_id' => $recruiter->id,
    ]);

    $vacancy->setRelation('recruiter', $recruiter);

    $event = new VacancyApplied($vacancy, $user);
    $listener = new SendCandidateNotification();

    $listener->handle($event);

    Notification::assertSentTo(
        $recruiter,
        NewCandidate::class,
        function ($notification, $channels) use ($vacancy, $user) {
            return $notification->vacancyId === $vacancy->id &&
                $notification->vacancyTitle === $vacancy->title &&
                $notification->candidateId === $user->id;
        }
    );
});
