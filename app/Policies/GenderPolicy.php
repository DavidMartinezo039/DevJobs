<?php

namespace App\Policies;

use App\Models\Gender;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GenderPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Gender $gender): bool
    {
        if ($gender->is_default) {
            return $user->hasRole('god');
        }

        return $user->hasRole('moderator') || $user->hasRole('god');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Gender $gender): bool
    {
        if ($gender->is_default) {
            return $user->hasRole('god');
        }

        return $user->hasRole('moderator') || $user->hasRole('god');
    }

    public function toggleDefault(User $user): bool
    {
        return $user->hasRole('god');
    }

}
