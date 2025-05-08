<?php

namespace App\Policies;

use App\Models\CV;
use App\Models\User;

class CvPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('god') || $user->hasPermissionTo('view cvs');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CV $cv): bool
    {
        if ($user->hasRole('god')) {
            return true;
        }

        if ($user->hasRole('developer') && $cv->user_id === $user->id) {
            return true;
        }

        return $user->hasRole('moderator') && ($cv->user_id === $user->id || $cv->user->hasRole('developer'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create cvs');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CV $cv): bool
    {
        return $user->id === $cv->user_id || ($user->hasRole('moderator') && $cv->user->hasRole('developer')) || $user->hasRole('god');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CV $cv): bool
    {
        return $user->id === $cv->user_id || ($user->hasRole('moderator') && $cv->user->hasRole('developer')) || $user->hasRole('god');
    }
}
