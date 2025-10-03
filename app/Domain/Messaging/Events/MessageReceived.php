<?php

namespace App\Domain\Messaging\Events;

use App\Domain\Messaging\Models\Message;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceived
{
    use Dispatchable, SerializesModels;

    public function __construct(public Message $message) {}
}
