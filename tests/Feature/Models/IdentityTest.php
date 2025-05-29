<?php

use App\Models\Identity;
use App\Models\PersonalData;

it('can create an identity with a type', function () {
    $identity = Identity::factory()->create(['type' => 'Passport']);

    expect($identity)->toBeInstanceOf(Identity::class)
        ->and($identity->type)->toBe('Passport');
});

it('identity has many-to-many relation with personalData including pivot number', function () {
    $identity = Identity::factory()->create();
    $personalData = PersonalData::factory()->create();

    $identity->personalData()->attach($personalData->id, ['number' => '123456789']);

    $identity->load('personalData');

    expect($identity->personalData)->toHaveCount(1);

    $related = $identity->personalData->first();

    expect($related->id)->toBe($personalData->id)
        ->and($related->pivot->number)->toBe('123456789');
});
