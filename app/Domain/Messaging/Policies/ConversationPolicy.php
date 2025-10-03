<?php

namespace App\Domain\Messaging\Policies;

use App\Domain\Messaging\Models\Conversation;
use App\Domain\Users\Models\User;

class ConversationPolicy
{
    /**
     * Admin can bypass all checks
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
    }

    /**
     * Determine if the user can view the conversation
     */
    public function view(User $user, Conversation $conversation): bool
    {
        // Admin bypass handled in before()
        return is_null($conversation->assigned_to) || $conversation->assigned_to === $user->id;
    }

    /**
     * Determine if the user can reply to the conversation
     */
    public function reply(User $user, Conversation $conversation): bool
    {
        // Only assigned staff can reply
        return $conversation->assigned_to === $user->id;
    }
}
