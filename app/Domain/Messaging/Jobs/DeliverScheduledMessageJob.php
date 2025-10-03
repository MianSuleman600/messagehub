<?php

namespace App\Domain\Messaging\Jobs;

use App\Domain\Messaging\Models\Message;
use App\Domain\Messaging\Models\ScheduledMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeliverScheduledMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [30, 60, 120];

    public function handle(): void
    {
        ScheduledMessage::where('status', 'scheduled')
            ->where('send_at', '<=', now())
            ->orderBy('send_at')
            ->chunkById(100, function ($messages) {
                foreach ($messages as $sm) {
                    DB::transaction(function () use ($sm) {
                        try {
                            $message = Message::create([
                                'conversation_id' => $sm->conversation_id,
                                'contact_id'      => $sm->contact_id,
                                'user_id'         => $sm->user_id,
                                'channel'         => $sm->channel,
                                'direction'       => 'outbound',
                                'body'            => $sm->body,
                                'status'          => 'queued',
                                'meta'            => ['scheduled_message_id' => $sm->id] + ($sm->meta ?? []),
                            ]);

                            $sm->update(['status' => 'queued']);

                            dispatch(new SendMessageJob($message->id))->onQueue('outbound');

                            Log::info('Scheduled message queued', ['scheduled_id' => $sm->id, 'message_id' => $message->id]);
                        } catch (\Throwable $e) {
                            Log::error('DeliverScheduledMessageJob failed', [
                                'scheduled_id' => $sm->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    });
                }
            });
    }
}
