<?php

use App\Events\DrivingLicenseEditRequested;
use App\Listeners\NotifyGodOfEditRequest;
use App\Models\DrivingLicense;
use App\Models\User;
use App\Notifications\EditRequestNotification;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

it('notifies all users with the god role when edit request event is fired', function () {
    Notification::fake();

    Role::findOrCreate('god');

    $god = User::factory()->create();
    $god->assignRole('god');

    $moderator = User::factory()->create();
    $license = DrivingLicense::factory()->create();

    $event = new DrivingLicenseEditRequested($moderator, $license);

    (new NotifyGodOfEditRequest)->handle($event);

    Notification::assertSentTo(
        $god,
        EditRequestNotification::class,
        function ($notification) use ($license, $moderator) {
            return $notification->drivingLicense->is($license) &&
                $notification->moderator->is($moderator);
        }
    );
});
