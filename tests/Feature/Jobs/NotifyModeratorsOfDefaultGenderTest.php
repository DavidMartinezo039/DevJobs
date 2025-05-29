<?php

use App\Jobs\NotifyModeratorsOfDefaultGender;
use App\Models\Gender;
use App\Models\User;
use App\Notifications\GenderDefaultStatusChangedNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();

    $this->gender = Gender::factory()->create();
});

it('notifies all moderators about the default gender change', function () {
    $moderator = User::factory()->create();
    $moderator->assignRole('moderator');

    $nonModerator = User::factory()->create();

    $job = new NotifyModeratorsOfDefaultGender($this->gender);
    $job->handle();

    Notification::assertSentTo($moderator, GenderDefaultStatusChangedNotification::class);
    Notification::assertNotSentTo($nonModerator, GenderDefaultStatusChangedNotification::class);
});
