<?php

use App\Http\Controllers\UserHistoryController;
use App\Jobs\GenerateUserHistoryPdf;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    Storage::fake('public');
    Queue::fake();
});

test('it dispatches the GenerateUserHistoryPdf job', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');
    $this->actingAs($user);

    $response = $this->post(route('user.history.generate'));
    $response->assertRedirect();
    $response->assertSessionHas('message', __('History in progress'));

    Queue::assertPushed(GenerateUserHistoryPdf::class, function ($job) use ($user) {
        return $job->requestedByUserId === $user->id;
    });
});

test('it redirects with error if no history files are found', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');
    $this->actingAs($user);

    $response = $this->get(action([App\Http\Controllers\UserHistoryController::class, 'download']));

    $response->assertRedirect();
    $response->assertSessionHas('error', __('No history files found'));
});
