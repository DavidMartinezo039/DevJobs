<?php

namespace App\Policies;

use App\Models\DrivingLicense;
use App\Models\EditRequest;
use App\Models\User;

class DrivingLicensePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DrivingLicense $drivingLicense): bool
    {
        return $this->extracted($drivingLicense, $user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DrivingLicense $drivingLicense): bool
    {
        return $this->extracted($drivingLicense, $user);
    }

    /**
     * @param DrivingLicense $drivingLicense
     * @param User $user
     * @return bool
     */
    public function extracted(DrivingLicense $drivingLicense, User $user): bool
    {
        if ($drivingLicense->only_god) {
            if ($user->hasRole('god')) {
                return true;
            }

            if ($user->hasRole('moderator')) {
                $approvedRequest = EditRequest::where('driving_license_id', $drivingLicense->id)
                    ->where('requested_by', $user->id)
                    ->where('approved', true)
                    ->first();

                return $approvedRequest !== null;
            }

            return false;
        }

        return $user->hasRole('moderator') || $user->hasRole('god');
    }
}
