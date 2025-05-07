<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\Response;

class VacancyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('god') || $user->hasPermissionTo('view vacancies');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vacancy $vacancy): bool
    {
        if ($user->hasRole('god')) {
            return true;
        }

        if ($user->hasRole('recruiter') && $vacancy->user_id === $user->id) {
            return true;
        }

        return $user->hasRole('moderator') && ($vacancy->user_id === $user->id || $vacancy->user->hasRole('recruiter'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create vacancies');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vacancy $vacancy): bool
    {
        return $user->id === $vacancy->user_id || ($user->hasRole('moderator') && $vacancy->user->hasRole('recruiter')) || $user->hasRole('god');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vacancy $vacancy): bool
    {
        return $user->id === $vacancy->user_id || ($user->hasRole('moderator') && $vacancy->user->hasRole('recruiter')) || $user->hasRole('god');
    }
}
