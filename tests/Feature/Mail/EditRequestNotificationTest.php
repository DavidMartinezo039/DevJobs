<?php

use App\Models\DrivingLicense;
use App\Models\User;
use App\Notifications\EditRequestNotification;
use Illuminate\Support\Facades\Notification;

it('sends an edit request notification via mail', function () {
    Notification::fake();

    $god = User::factory()->create(['name' => 'God User']);
    $moderator = User::factory()->create(['name' => 'Moderator']);
    $license = DrivingLicense::factory()->create(['category' => 'A2']);

    $god->notify(new EditRequestNotification($license, $moderator));

    Notification::assertSentTo(
        $god,
        EditRequestNotification::class,
        function ($notification, $channels) use ($license, $moderator, $god) {
            expect($channels)->toContain('mail');

            $mail = $notification->toMail($god);

            expect($mail->subject)->toBe(__('Edit Request for Driving License'))
                ->and($mail->greeting)->toBe("Hello {$god->name},")
                ->and($mail->introLines)->toContain(
                    __('The user ":user" has requested to edit the driving license ":license".', [
                        'user' => $moderator->name,
                        'license' => $license->category,
                    ])
                );
            return true;
        }
    );
});
