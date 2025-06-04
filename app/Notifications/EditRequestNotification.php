<?php

namespace App\Notifications;

use App\Models\DrivingLicense;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EditRequestNotification extends Notification
{
    use Queueable;

    public $drivingLicense;
    public $moderator;

    public function __construct(DrivingLicense $drivingLicense, User $moderator)
    {
        $this->drivingLicense = $drivingLicense;
        $this->moderator = $moderator;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('Edit Request for Driving License'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('The user ":user" has requested to edit the driving license ":license".', [
                'user' => $this->moderator->name,
                'license' => $this->drivingLicense->category,
            ]))
            ->line(__('Please review the request as soon as possible.'));
    }
}
