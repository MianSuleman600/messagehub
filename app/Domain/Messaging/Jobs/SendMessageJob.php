<?php

namespace App\Domain\Messaging\Jobs;

use App\Domain\Messaging\Actions\SendMessage;
use App\Domain\Messaging\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    public function __construct(public int $messageId) {}

    public function handle(SendMessage $action): void
    {
        $message = Message::with('contact')->find($this->messageId);

        if (!$message || $message->status !== 'queued') {
            Log::warning('SendMessageJob skipped: invalid message or wrong status', ['message_id' => $this->messageId]);
            return;
        }

        try {
            $message->update(['status' => 'sending']);
            $action->handle($message);
            $message->update(['status' => 'sent']);
            Log::info('SendMessageJob completed', ['message_id' => $this->messageId]);
        } catch (\Throwable $e) {
            $message->update(['status' => 'failed']);
            Log::error('SendMessageJob failed', [
                'message_id' => $this->messageId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
