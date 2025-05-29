<?php

use App\Models\SocialMedia;
use App\Models\PersonalData;

it('has fillable type attribute', function () {
    $socialMedia = new SocialMedia();

    expect($socialMedia->getFillable())->toContain('type');
});

it('belongs to many personal data', function () {
    $socialMedia = SocialMedia::factory()->create();
    $personalData = PersonalData::factory()->create();

    $socialMedia->personalData()->attach($personalData->id, [
        'user_name' => 'testuser',
        'url' => 'https://example.com/testuser'
    ]);

    $socialMedia->load('personalData');

    expect($socialMedia->personalData)->toHaveCount(1);

    $related = $socialMedia->personalData->first();

    expect($related)->toBeInstanceOf(PersonalData::class)
        ->and($related->pivot->user_name)->toBe('testuser')
        ->and($related->pivot->url)->toBe('https://example.com/testuser');
});
