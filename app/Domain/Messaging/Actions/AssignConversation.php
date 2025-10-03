<?php

namespace App\Domain\Messaging\Actions;

use App\Domain\Messaging\Models\Conversation;
use App\Domain\Users\Models\StaffActivity;
use App\Domain\Users\Models\User;
use App\Domain\Messaging\Notifications\ConversationAssignedNotification;

class AssignConversation
{
    public function handle(Conversation $conversation, ?User $assignee, User $actor): Conversation
    {
        $conversation->assigned_to = $assignee?->id;
        $conversation->save();

        StaffActivity::create([
            'user_id' => $actor->id,
            'action' => 'conversation.assigned',
            'subject_type' => Conversation::class,
            'subject_id' => $conversation->id,
            'meta' => [
                'assigned_to' => $assignee?->only(['id', 'name']),
            ],
        ]);

        // Notify assignee via email (queued)
        if ($assignee && $assignee->email) {
            $assignee->notify(new ConversationAssignedNotification($conversation));
        }

        event(new \App\Domain\Messaging\Events\ConversationAssigned($conversation, $actor, $assignee));

        return $conversation;
    }
}
