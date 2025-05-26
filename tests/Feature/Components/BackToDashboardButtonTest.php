<?php

use App\Models\User;
use function Pest\Laravel\actingAs;

test('render back to dashboard button', function () {
    $user = User::factory()->create()->assignRole('moderator');
    actingAs($user);

    $response = $this->get('/dashboard/genders');

    $response->assertStatus(200);
});
