<?php

use App\Livewire\CvManager;
use App\Models\CV;
use App\Models\PersonalData;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('user with CV can access show', function () {
    $user = createUserWithCompleteCv('developer');
    actingAs($user);

    $cv = $user->cvs()->first();

    PersonalData::factory()->create([
        'cv_id' => $cv->id,
        'image' => 'default.jpg',
    ]);

    Livewire::test(CvManager::class)
        ->set('selectedCv', $cv)
        ->call('show', $cv)
        ->assertSet('view', 'show');
});
