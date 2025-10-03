<?php

namespace App\Domain\Messaging\Actions;

use App\Domain\Contacts\Models\Contact;
use App\Domain\Messaging\Models\Conversation;
use App\Domain\Messaging\Models\Message;
use Illuminate\Support\Facades\DB;

class ReceiveMessage
{
    public function handle(array $payload): Message
    {
        return DB::transaction(function () use ($payload) {
            $contact = Contact::firstOrCreate(
                [
                    'handle' => $payload['handle'] ?? null,
                    'email' => $payload['email'] ?? null,
                    'phone' => $payload['phone'] ?? null
                ],
                [
                    'name' => $payload['name'] ?? ($payload['handle'] ?? 'Unknown')
                ]
            );

            $contact->last_seen_at = now();
            $contact->save();

            $conversation = Conversation::query()
                ->where('contact_id', $contact->id)
                ->where('channel', $payload['channel'])
                ->first();

            if (! $conversation) {
                $conversation = Conversation::create([
                    'contact_id' => $contact->id,
                    'channel' => $payload['channel'],
                    'status' => 'open',
                    'last_message_at' => now(),
                ]);
            }

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'contact_id' => $contact->id,
                'user_id' => null,
                'channel' => $payload['channel'],
                'external_id' => $payload['external_id'] ?? null,
                'direction' => 'inbound',
                'body' => $payload['body'] ?? null,
                'status' => 'delivered',
                'received_at' => $payload['received_at'] ?? now(),
                'meta' => $payload['meta'] ?? [],
            ]);

            $conversation->last_message_at = now();
            $conversation->save();

            event(new \App\Domain\Messaging\Events\MessageReceived($message));

            return $message;
        });
    }
}
