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

    public int $vacancyId;
    public string $vacancyTitle;
    public int $candidateId;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $vacancyId, string $vacancyTitle, int $candidateId)
    {
        $this->vacancyId = $vacancyId;
        $this->vacancyTitle = $vacancyTitle;
        $this->candidateId = $candidateId;
    }

    /**
     * Get the notification's delivery channels.
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
            ->line(__('You have received a new candidate for your vacancy.'))
            ->line(__('The vacancy is: :name', ['name' => $this->vacancyTitle]))
            ->action(__('See notification'), $url)
            ->line(__('Thank you for using DevJobs.'));
    }

    /**
     * Get the array representation of the notification for storage.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'vacancy_id'   => $this->vacancyId,
            'vacancy_title'=> $this->vacancyTitle,
            'candidate_id' => $this->candidateId,
        ];
    }
}
