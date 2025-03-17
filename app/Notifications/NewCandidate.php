<?php

namespace App\Notifications;

use AllowDynamicProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCandidate extends Notification
{
    use Queueable;

    private mixed $id_vacancy;
    private mixed $name_vacancy;
    private mixed $user_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($id_vacancy, $name_vacancy, $user_id)
    {
        $this->id_vacancy = $id_vacancy;
        $this->name_vacancy = $name_vacancy;
        $this->user_id = $user_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/notifications');

        return (new MailMessage)
            ->line('You have received a new candidate for your vacancy.')
            ->line('The vacancy is: ' . $this->name_vacancy)
            ->action('See notification', $url)
            ->line('Thank you for using DevJobs');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'id_vacancy' => $this->id_vacancy,
            'name_vacancy' => $this->name_vacancy,
            'user_id' => $this->user_id,
        ];
    }
}
