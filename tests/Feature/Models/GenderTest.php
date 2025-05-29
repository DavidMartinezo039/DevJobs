<?php

use App\Models\Gender;
use App\Models\PersonalData;

it('can create a gender with a type', function () {
    $gender = Gender::factory()->create(['type' => 'Male']);

    expect($gender)->toBeInstanceOf(Gender::class)
        ->and($gender->type)->toBe('Male');
});

it('gender has many personalData records', function () {
    $gender = Gender::factory()->create();
    $personalDataItems = PersonalData::factory()->count(3)->create([
        'gender_id' => $gender->id,
    ]);

    $gender->load('personalData');

    expect($gender->personalData)->toHaveCount(3);

    foreach ($gender->personalData as $personalData) {
        expect($personalData->gender_id)->toBe($gender->id);
    }
});
