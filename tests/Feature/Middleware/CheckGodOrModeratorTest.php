<?php

use App\Http\Middleware\CheckGodOrModerator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    Route::middleware(CheckGodOrModerator::class)
        ->get('/test-middleware', fn() => 'OK')
        ->name('test.middleware');
});

it('allows user with god role', function () {
    $user = User::factory()->create();
    $user->assignRole('god');

    actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

it('allows user with moderator role', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');

    actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

it('redirects user without roles to home', function () {
    $user = User::factory()->create();
    $user->assignRole('developer');

    actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('home'));
});

it('returns 403 json response for unauthorized user expecting json', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->getJson(route('dashboard'))
        ->assertStatus(403)
        ->assertJson([
            'message' => 'You are not authorized to view this page.'
        ]);
});
