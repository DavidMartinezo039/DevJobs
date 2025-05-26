<?php

use App\Livewire\CvManager;
use App\Models\CV;
use App\Models\PersonalData;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('user with CV can access show', function () {
    $user = createUserWithCompleteCv('developer');

    actingAs($user);

    $selectedCv = $user->cvs()->first();

    $personalData = PersonalData::factory()->create([
        'cv_id' => $selectedCv->id,
        'image' => 'default.jpg',
    ]);

    Livewire::test(CvManager::class)
        ->call('show', $selectedCv)
        ->assertOk();
});
