<?php

namespace App\Domain\Messaging\Events;

use App\Domain\Messaging\Models\Conversation;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationAssigned
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Conversation $conversation,
        public User $actor,
        public ?User $assignee
    ) {}
}
