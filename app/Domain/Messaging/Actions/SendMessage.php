<?php

namespace App\Domain\Messaging\Actions;

use App\Domain\Messaging\Models\Message;
use App\Integrations\Channels\ChannelManager;
use Illuminate\Support\Facades\DB;

class SendMessage
{
    public function __construct(private ChannelManager $manager) {}

    public function handle(Message $message): Message
    {
        return DB::transaction(function () use ($message) {
            $client = $this->manager->clientFor($message->channel);

            $response = $client->send([
                'to' => $message->contact,
                'body' => $message->body,
                'meta' => $message->meta ?? [],
            ]);

            $message->status = $response->ok ? 'sent' : 'failed';
            $message->external_id = $response->external_id ?? $message->external_id;
            $message->sent_at = now();
            $message->save();

            event(new \App\Domain\Messaging\Events\MessageSent($message));

            return $message;
        });
    }
}
