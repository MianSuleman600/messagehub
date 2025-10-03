<?php

namespace App\Domain\Messaging\Listeners;

use App\Domain\Messaging\Actions\ApplyAutoReply;
use App\Domain\Messaging\Jobs\SendMessageJob;
use App\Domain\Messaging\Models\Message;

class TriggerAutoReply
{
    public function __construct(private ApplyAutoReply $apply) {}

    public function handle(\App\Domain\Messaging\Events\MessageReceived $event): void
    {
        $inbound = $event->message;

        $plan = $this->apply->handle($inbound);

        if (! $plan) return;

        $reply = Message::create([
            'conversation_id' => $inbound->conversation_id,
            'contact_id' => $inbound->contact_id,
            'user_id' => null,
            'channel' => $inbound->channel,
            'direction' => 'outbound',
            'body' => $plan['body'],
            'status' => 'queued',
            'meta' => ['auto_reply_rule_id' => $plan['rule_id']],
        ]);

        dispatch(new SendMessageJob($reply->id))->onQueue('outbound');
    }
}
