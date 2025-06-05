<?php

use App\Models\User;
use App\Models\DrivingLicense;

function actingAsGod(): User {
    $user = User::factory()->create();
    $user->assignRole('god');
    return $user;
}

it('can list driving licenses', function () {
    actingAsGod();

    DrivingLicense::factory()->count(3)->create();

    $response = $this->actingAs(actingAsGod(), 'sanctum')
        ->getJson('/api/driving-licenses');

    $response->assertOk();
});

it('can create a new driving license', function () {
    $user = actingAsGod();

    $data = [
        'category' => 'B',
        'vehicle_type' => 'Car',
        'max_speed' => 120,
        'max_power' => 200,
        'power_to_weight' => 10,
        'max_weight' => 3500,
        'max_passengers' => 5,
        'min_age' => 18,
    ];

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/driving-licenses', $data);

    $response->assertCreated()
        ->assertJsonFragment(['category' => 'B']);

    expect(DrivingLicense::where('category', 'B')->exists())->toBeTrue();
});

it('can view a single driving license', function () {
    $user = actingAsGod();
    $license = DrivingLicense::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->getJson("/api/driving-licenses/{$license->id}");

    $response->assertOk()
        ->assertJsonFragment(['id' => $license->id]);
});

it('can update a driving license', function () {
    $user = actingAsGod();
    $license = DrivingLicense::factory()->create([
        'category' => 'A'
    ]);

    $updateData = [
        'category' => 'A2',
        'vehicle_type' => $license->vehicle_type,
        'max_speed' => $license->max_speed,
        'max_power' => $license->max_power,
        'power_to_weight' => $license->power_to_weight,
        'max_weight' => $license->max_weight,
        'max_passengers' => $license->max_passengers,
        'min_age' => $license->min_age,
    ];

    $response = $this->actingAs($user, 'sanctum')
        ->putJson("/api/driving-licenses/{$license->id}", $updateData);

    $response->assertOk()
        ->assertJsonFragment(['category' => 'A2']);
});

it('can delete a driving license', function () {
    $user = actingAsGod();
    $license = DrivingLicense::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->deleteJson("/api/driving-licenses/{$license->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Driving license deleted successfully.']);

    expect(DrivingLicense::find($license->id))->toBeNull();
});
