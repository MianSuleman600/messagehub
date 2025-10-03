<?php

namespace App\Domain\Messaging\Listeners;

use App\Domain\Messaging\Events\MessageReceived;

class CreateOrUpdateContactOnIncoming
{
    /**
     * Update the contact's last_seen_at timestamp when a new message is received.
     */
    public function handle(MessageReceived $event): void
    {
        $message = $event->message;
        $contact = $message->contact;

        $contact->last_seen_at = now();
        $contact->save();
    }
}
