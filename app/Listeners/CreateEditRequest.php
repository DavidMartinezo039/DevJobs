<?php

namespace App\Listeners;

use App\Events\DrivingLicenseEditRequested;
use App\Models\EditRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateEditRequest
{
    public function handle(DrivingLicenseEditRequested $event)
    {
        EditRequest::create([
            'driving_license_id' => $event->drivingLicense->id,
            'requested_by' => $event->moderator->id,
        ]);
    }
}
