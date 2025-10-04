<?php

namespace App\Domain\Messaging\Jobs;

use App\Domain\Messaging\Actions\ReceiveMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessIncomingMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    public function __construct(public array $payload) {}

    public function handle(ReceiveMessage $receiver): void
    {
        try {
            $receiver->handle($this->payload);
            Log::info('ProcessIncomingMessageJob completed', ['payload' => $this->payload]);
        } catch (\Throwable $e) {
            Log::error('ProcessIncomingMessageJob failed', [
                'payload' => $this->payload,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // allows retry
        }
    }
}
