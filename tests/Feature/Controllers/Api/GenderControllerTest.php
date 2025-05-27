<?php

use App\Models\User;
use App\Models\Gender;
use Illuminate\Support\Facades\Queue;
use App\Jobs\NotifyMarketingUsersOfGenderChange;
use App\Jobs\NotifyModeratorsOfDefaultGender;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

test('authorized users can list all genders', function () {
    loginAs('moderator');

    Gender::factory()->count(3)->create();

    $response = getJson('/api/genders');

    $response->assertOk()
        ->assertJsonCount(3);
});

test('unauthorized users cannot access gender index', function () {
    loginAs('developer');

    $response = getJson('/api/genders');

    $response->assertForbidden();
});

test('authorized users can view a gender', function () {
    loginAs('god');

    $gender = Gender::factory()->create();

    $response = getJson("/api/genders/{$gender->id}");

    $response->assertOk()
        ->assertJson([
            'id' => $gender->id,
            'type' => $gender->type,
        ]);
});

test('unauthorized users cannot view a gender', function () {
    loginAs('developer');

    $gender = Gender::factory()->create();

    $response = getJson("/api/genders/{$gender->id}");

    $response->assertForbidden();
});

test('authorized users can create a gender', function () {
    Queue::fake();
    loginAs('god');

    $payload = ['type' => 'No binario', 'is_default' => false];

    $response = postJson('/api/genders', $payload);

    $response->assertCreated()
        ->assertJsonFragment(['type' => 'No binario']);

    $this->assertDatabaseHas('genders', ['type' => 'No binario']);
    Queue::assertPushed(NotifyMarketingUsersOfGenderChange::class);
});

test('unauthorized users cannot create a gender', function () {
    loginAs('developer');

    $response = postJson('/api/genders', ['type' => 'Otro']);

    $response->assertForbidden();
});

test('authorized users can update a gender', function () {
    Queue::fake();
    $user = loginAs('god');

    $gender = Gender::factory()->create([
        'type' => 'Original',
    ]);
    $this->withoutExceptionHandling();

    $response = putJson("/api/genders/{$gender->id}", [
        'type' => 'Actualizado'
    ]);

    $response->assertOk()
        ->assertJsonFragment(['type' => 'Actualizado']);

    $this->assertDatabaseHas('genders', ['type' => 'Actualizado']);
    Queue::assertPushed(NotifyMarketingUsersOfGenderChange::class);
});

test('unauthorized users cannot update a gender', function () {
    loginAs('developer');

    $gender = Gender::factory()->create();

    $response = putJson("/api/genders/{$gender->id}", [
        'type' => 'Hackeado',
        'is_default' => false,
    ]);

    $response->assertForbidden();
});

test('authorized users can delete a gender', function () {
    Queue::fake();
    loginAs('god');

    $gender = Gender::factory()->create();

    $response = deleteJson("/api/genders/{$gender->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Gender deleted']);

    $this->assertDatabaseMissing('genders', ['id' => $gender->id]);
    Queue::assertPushed(NotifyMarketingUsersOfGenderChange::class);
});

test('unauthorized users cannot delete a gender', function () {
    loginAs('developer');

    $gender = Gender::factory()->create();

    $response = deleteJson("/api/genders/{$gender->id}");

    $response->assertForbidden();
});

test('god can toggle gender default state', function () {
    Queue::fake();
    loginAs('god');

    $gender = Gender::factory()->create(['is_default' => false]);

    $response = postJson("/api/genders/{$gender->id}/toggle-default");

    $response->assertOk()
        ->assertJsonFragment(['is_default' => true]);

    $this->assertDatabaseHas('genders', ['id' => $gender->id, 'is_default' => true]);
    Queue::assertPushed(NotifyModeratorsOfDefaultGender::class);
});

test('non-god users cannot toggle gender default state', function () {
    loginAs('moderator');

    $gender = Gender::factory()->create();

    $response = postJson("/api/genders/{$gender->id}/toggle-default");

    $response->assertForbidden();
});
