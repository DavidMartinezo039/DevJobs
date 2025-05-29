<?php

use App\Models\WorkExperience;
use App\Models\CV;

it('creates a work experience record', function () {
    $cv = CV::factory()->create();

    $workExperience = WorkExperience::create([
        'cv_id' => $cv->id,
        'company_name' => 'OpenAI',
        'position' => 'Developer',
        'start_date' => '2023-01-01',
        'end_date' => '2024-01-01',
        'description' => 'Worked on AI models.'
    ]);

    expect($workExperience)->toBeInstanceOf(WorkExperience::class)
        ->and($workExperience->company_name)->toBe('OpenAI')
        ->and($workExperience->cv_id)->toBe($cv->id);
});

it('belongs to a cv', function () {
    $cv = CV::factory()->create();
    $workExperience = WorkExperience::factory()->create([
        'cv_id' => $cv->id,
    ]);

    expect($workExperience->cv)->toBeInstanceOf(CV::class)
        ->and($workExperience->cv->id)->toBe($cv->id);
});
