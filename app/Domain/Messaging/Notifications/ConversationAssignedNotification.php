<?php

namespace App\Domain\Messaging\Notifications;

use App\Domain\Messaging\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConversationAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Conversation $conversation) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New conversation assigned to you')
            ->line('A conversation has been assigned to you.')
            ->action('Open Conversation', route('inbox.conversation', $this->conversation));
    }
}
