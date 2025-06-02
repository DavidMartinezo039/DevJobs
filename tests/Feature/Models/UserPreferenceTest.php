<?php

use App\Models\UserPreference;
use App\Models\User;
use App\Models\Category;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('has a user relationship that returns BelongsTo', function () {
    $preference = new UserPreference();
    $relation = $preference->user();

    expect($relation)->toBeInstanceOf(BelongsTo::class);
});

it('has a category relationship that returns BelongsTo', function () {
    $preference = new UserPreference();
    $relation = $preference->category();

    expect($relation)->toBeInstanceOf(BelongsTo::class);
});

it('has a salary relationship that returns BelongsTo', function () {
    $preference = new UserPreference();
    $relation = $preference->salary();

    expect($relation)->toBeInstanceOf(BelongsTo::class);
});
