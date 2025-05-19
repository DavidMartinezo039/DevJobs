<?php

namespace App\Notifications;

use App\Models\Gender;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GenderChangedNotification extends Notification
{
    use Queueable;

    public Gender $gender;
    public string $action;

    public function __construct(Gender $gender, string $action)
    {
        $this->gender = $gender;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $messages = [
            'created' => __("A new gender has been created: :gender.", ['gender' => $this->gender->type]),
            'updated' => __("The gender ':gender' has been updated.", ['gender' => $this->gender->type]),
            'deleted' => __("The gender ':gender' has been deleted.", ['gender' => $this->gender->type]),
        ];

        return (new MailMessage)
            ->subject(__('Gender Update Notification'))
            ->line($messages[$this->action] ?? __('Gender has been updated.'));
    }
}
