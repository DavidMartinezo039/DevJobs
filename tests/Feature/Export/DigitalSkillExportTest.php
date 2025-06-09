<?php

use App\Exports\DigitalSkillsExport;
use App\Models\DigitalSkill;

it('DigitalSkillsExport includes trashed skills', function () {
    $skill = DigitalSkill::factory()->create();
    $skill->delete();

    $export = new DigitalSkillsExport();
    $collection = $export->collection();

    expect($collection->contains('id', $skill->id))->toBeTrue();
});

it('returns correct headings for digital skills export', function () {
    $export = new DigitalSkillsExport();

    $headings = $export->headings();

    expect($headings)->toBe([
        'ID',
        'Name',
        'Deleted At',
        'Created At',
        'Updated At',
    ]);
});
