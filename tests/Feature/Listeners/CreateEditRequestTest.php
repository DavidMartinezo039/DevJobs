<?php

use App\Events\DrivingLicenseEditRequested;
use App\Listeners\CreateEditRequest;
use App\Models\DrivingLicense;
use App\Models\EditRequest;
use App\Models\User;

it('creates an edit request when the event is handled', function () {
    $moderator = User::factory()->create();
    $license = DrivingLicense::factory()->create();

    $event = new DrivingLicenseEditRequested($moderator, $license);

    (new CreateEditRequest)->handle($event);

    $this->assertDatabaseHas('edit_requests', [
        'driving_license_id' => $license->id,
        'requested_by' => $moderator->id,
    ]);
});
