<?php

use App\Models\Education;
use App\Models\CV;

it('education belongs to a CV', function () {
    $cv = CV::factory()->create();

    $education = Education::factory()->create([
        'cv_id' => $cv->id,
    ]);

    expect($education->cv->id)->toBe($cv->id);
});
