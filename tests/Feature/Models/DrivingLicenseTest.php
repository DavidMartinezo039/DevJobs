<?php

use App\Models\DrivingLicense;
use App\Models\CV;

it('driving license belongs to many CVs', function () {
    $license = DrivingLicense::factory()->create();

    $cvs = CV::factory()->count(3)->create();

    $license->cvs()->attach($cvs->pluck('id'));

    $license->load('cvs');

    expect($license->cvs)->toHaveCount(3);

    foreach ($license->cvs as $cv) {
        expect($cvs->pluck('id'))->toContain($cv->id);
    }
});
