<?php

use App\Models\User;
use App\Models\Vacancy;
use App\Models\Cv;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

it('has a vacancies relationship that returns BelongsToMany', function () {
    $user = new User();
    $relation = $user->vacancies();

    expect($relation)->toBeInstanceOf(BelongsToMany::class);
});

it('has a cvs relationship that returns HasMany', function () {
    $user = new User();
    $relation = $user->cvs();

    expect($relation)->toBeInstanceOf(HasMany::class);
});

it('has a preference relationship that returns HasOne', function () {
    $user = new User();
    $relation = $user->preference();

    expect($relation)->toBeInstanceOf(HasOne::class);
});
