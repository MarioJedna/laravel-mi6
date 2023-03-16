<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionOutcomeUpdated extends Notification
{
    use Queueable;


    public $var;

    /**
     * Create a new notification instance.
     */
    public function __construct($variable)
    {
        $this->var = $variable;
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
        return (new MailMessage)
            ->line('Hello admin ' . $notifiable->name)
            ->action('Selfdectruction', url('/'))
            ->line("Mission # " . $this->var->id . " - " . $this->var->name . " outcome has been updated to " . ($this->var->outcome ? "ok" : "not ok") . ".")
            ->line('Thank you for your work agent.');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'some_info' => $this->var
        ];
    }
}
