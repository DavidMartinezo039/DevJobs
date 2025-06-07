<?php

use App\Models\ActivityLog;
use App\Models\User;

test('se puede crear un registro de activity log con atributos válidos', function () {
    $user = User::factory()->create();

    $activity = ActivityLog::create([
        'user_id' => $user->id,
        'role' => 'admin',
        'action' => 'created',
        'target_type' => 'Post',
        'target_id' => 1,
        'description' => 'Created a new post',
    ]);

    expect($activity)->toBeInstanceOf(ActivityLog::class)
        ->and($activity->user_id)->toBe($user->id)
        ->and($activity->role)->toBe('admin')
        ->and($activity->action)->toBe('created')
        ->and($activity->target_type)->toBe('Post')
        ->and($activity->target_id)->toBe(1)
        ->and($activity->description)->toBe('Created a new post');
});

test('la relación user devuelve una instancia de User', function () {
    $user = User::factory()->create();

    $activity = ActivityLog::factory()->for($user)->create();

    expect($activity->user)->toBeInstanceOf(User::class);
});

test('user_id puede ser nulo si no se asigna usuario', function () {
    $activity = ActivityLog::create([
        'role' => 'guest',
        'action' => 'viewed',
        'target_type' => 'Page',
        'target_id' => 5,
        'description' => 'Viewed a page',
    ]);

    expect($activity->user)->toBeNull();
});
