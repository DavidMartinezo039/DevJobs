<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;

test('can register a user', function () {
    $response = postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email'],
            'token',
        ]);

    expect(User::where('email', 'test@example.com')->exists())->toBeTrue();
});

test('fails registration with invalid data', function () {
    $response = postJson('/api/register', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
    ]);

    $response->assertStatus(422);
});

test('can login with valid credentials', function () {
    $user = User::create([
        'name' => 'Existing User',
        'email' => 'user@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson('/api/login', [
        'email' => 'user@example.com',
        'password' => 'password123',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email'],
            'token',
        ]);
});

test('fails login with invalid credentials', function () {
    $user = User::create([
        'name' => 'Existing User',
        'email' => 'user@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson('/api/login', [
        'email' => 'user@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422);
});

test('can logout an authenticated user', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = postJson('/api/logout');

    $response->assertOk()
        ->assertJson(['message' => 'Logout']);
});

test('can get profile of authenticated user', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = getJson('/api/profile');

    $response->assertOk()
        ->assertJson([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ]);
});

test('fails to access profile without token', function () {
    $response = getJson('/api/profile');

    $response->assertUnauthorized();
});

it('calls the artisan command and flashes the token output', function () {
    $user = User::factory()->create();

    Artisan::shouldReceive('call')
        ->once()
        ->with('user:show-token', ['--id' => $user->id]);

    Artisan::shouldReceive('output')
        ->once()
        ->andReturn('ThisIsAFakeToken123');

    actingAs($user);

    $response = $this->get(route('profile.token'));

    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHas('token', 'ThisIsAFakeToken123');
});
