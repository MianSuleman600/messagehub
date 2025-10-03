<?php

namespace App\Domain\Messaging\Queries;

use App\Domain\Messaging\Models\Conversation;
use App\Domain\Messaging\Models\Message;
use App\Models\User; // <-- updated User import
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MessageQueries
{
    /**
     * Get a paginated list of conversations visible to the user, with filters applied
     *
     * @param User $user
     * @param array $filters ['channel' => ?, 'status' => ?, 'assignee' => ?, 'q' => ?]
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public static function conversationIndex(User $user, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Conversation::query()
            ->visibleTo($user) // Ensure you have a visibleTo scope
            ->with([
                'contact:id,name,handle,email,phone',
                'assignee:id,name'
            ])
            ->when($filters['channel'] ?? null, fn($q, $channel) => $q->where('channel', $channel))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when(($filters['assignee'] ?? null) === 'me', fn($q) => $q->where('assigned_to', $user->id))
            ->when(($filters['assignee'] ?? null) === 'unassigned', fn($q) => $q->whereNull('assigned_to'))
            ->when($filters['q'] ?? null, function ($q, $term) {
                $q->whereHas('contact', function ($c) use ($term) {
                        $c->where('name', 'like', "%{$term}%")
                          ->orWhere('handle', 'like', "%{$term}%")
                          ->orWhere('email', 'like', "%{$term}%");
                    })
                    ->orWhereHas('messages', function ($m) use ($term) {
                        $m->where('body', 'like', "%{$term}%");
                    });
            })
            ->orderByDesc('last_message_at');

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get all messages in a conversation, ordered chronologically
     *
     * @param Conversation $conversation
     * @return Collection
     */
    public static function conversationThread(Conversation $conversation): Collection
    {
        return Message::query()
            ->where('conversation_id', $conversation->id)
            ->with(['user:id,name'])
            ->orderBy('created_at')
            ->get();
    }
}
