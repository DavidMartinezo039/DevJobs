<?php

use App\Models\Phone;
use App\Models\PersonalData;

it('can create a phone with a type', function () {
    $phone = Phone::factory()->create(['type' => 'mobile']);

    expect($phone)->toBeInstanceOf(Phone::class)
        ->and($phone->type)->toBe('mobile');
});

it('phone has many-to-many relation with personalData', function () {
    $phone = Phone::factory()->create();
    $personalData = PersonalData::factory()->create();

    $phone->personalData()->attach($personalData->id, ['number' => '123456789']);

    $phone->load('personalData');

    expect($phone->personalData)->toHaveCount(1);

    $related = $phone->personalData->first();

    expect($related->id)->toBe($personalData->id)
        ->and($related->pivot->number)->toBe('123456789');
});
