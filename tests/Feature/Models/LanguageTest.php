<?php

use App\Models\Language;
use App\Models\CV;

it('can create a language with a name', function () {
    $language = Language::factory()->create(['name' => 'Spanish']);

    expect($language)->toBeInstanceOf(Language::class)
        ->and($language->name)->toBe('Spanish');
});

it('language has many-to-many relation with cvs including pivot level', function () {
    $language = Language::factory()->create();
    $cv = CV::factory()->create();

    $language->cvs()->attach($cv->id, ['level' => 'Advanced']);

    $language->load('cvs');

    expect($language->cvs)->toHaveCount(1);

    $related = $language->cvs->first();

    expect($related->id)->toBe($cv->id)
        ->and($related->pivot->level)->toBe('Advanced');
});
