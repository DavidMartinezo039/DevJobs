<?php

use App\Jobs\NotifyMarketingUsersOfGenderChange;
use App\Models\Gender;
use App\Models\User;
use App\Notifications\GenderChangedNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();

    $this->gender = Gender::factory()->create();
});

it('notifies users who want marketing and moderators about gender changes', function () {
    $marketingUser = User::factory()->create(['wants_marketing' => true]);

    $moderatorUser = User::factory()->create(['wants_marketing' => false]);
    $moderatorUser->assignRole('moderator');

    $normalUser = User::factory()->create(['wants_marketing' => false]);

    $job = new NotifyMarketingUsersOfGenderChange($this->gender, 'created');
    $job->handle();

    Notification::assertSentTo($marketingUser, GenderChangedNotification::class);
    Notification::assertSentTo($moderatorUser, GenderChangedNotification::class);

    Notification::assertNotSentTo($normalUser, GenderChangedNotification::class);
});
