<?php

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->assignRole('recruiter');

    actingAs($this->user);
});

it('shows unread notifications and marks them as read for recruiter role', function () {
    $this->user->notifications()->saveMany([
        new DatabaseNotification([
            'id' => '1',
            'type' => 'TestNotification',
            'notifiable_id' => $this->user->id,
            'notifiable_type' => get_class($this->user),
            'data' => ['name_vacancy' => 'Developer', 'id_vacancy' => 123],
            ]),
        new DatabaseNotification([
            'id' => '2',
            'type' => 'TestNotification',
            'notifiable_id' => $this->user->id,
            'notifiable_type' => get_class($this->user),
            'data' => ['name_vacancy' => 'Developer', 'id_vacancy' => 123],
            ]),
    ]);

    expect($this->user->unreadNotifications)->toHaveCount(2);

    $response = $this->get(route('notifications.index'));

    $response->assertStatus(200)
        ->assertViewIs('notifications.index')
        ->assertViewHas('notifications', function ($notifications) {
            return $notifications->count() === 2;
        });

    $this->user->refresh();
    expect($this->user->unreadNotifications)->toHaveCount(0);
});
