<?php

namespace App\Policies;

use App\Models\DigitalSkill;
use App\Models\User;

class DigitalSkillPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DigitalSkill $digitalSkill): bool
    {
        return $user->hasRole('moderator') || $user->hasRole('god');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DigitalSkill $digitalSkill): bool
    {
        return $user->hasRole('moderator') || $user->hasRole('god');
    }
}
