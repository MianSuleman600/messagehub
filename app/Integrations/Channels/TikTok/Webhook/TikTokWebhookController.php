<?php

namespace App\Integrations\Channels\TikTok\Webhook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Domain\Messaging\Jobs\ProcessIncomingMessageJob;

class TikTokWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('TikTok Webhook received', ['payload' => $payload]);

        // Dispatch job to process message
        ProcessIncomingMessageJob::dispatch([
            'channel' => 'tiktok',
            'payload' => $payload,
        ])->onQueue('inbound');

        return response('OK', 200);
    }
}
