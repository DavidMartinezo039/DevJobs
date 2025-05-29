<?php

use App\Models\DigitalSkill;
use App\Models\CV;

it('digital skill belongs to many CVs', function () {
    $digitalSkill = DigitalSkill::factory()->create();

    $cvs = CV::factory()->count(3)->create();

    $digitalSkill->cvs()->attach($cvs->pluck('id'));

    $digitalSkill->load('cvs');

    expect($digitalSkill->cvs)->toHaveCount(3);

    foreach ($digitalSkill->cvs as $cv) {
        expect($cvs->pluck('id'))->toContain($cv->id);
    }
});
