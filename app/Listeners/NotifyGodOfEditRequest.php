<?php

namespace App\Listeners;

use App\Events\DrivingLicenseEditRequested;
use App\Models\User;
use App\Notifications\EditRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyGodOfEditRequest
{
    public function handle(DrivingLicenseEditRequested $event)
    {
        $gods = User::role('god')->get();
        Notification::send($gods, new EditRequestNotification($event->drivingLicense, $event->moderator));
    }
}
