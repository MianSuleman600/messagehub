<?php

namespace App\Integrations\OAuth\TikTok;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Messaging\Jobs\ProcessIncomingMessageJob;
use Illuminate\Support\Facades\Log;

class TikTokWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('TikTok Webhook received', ['payload' => $payload]);

        ProcessIncomingMessageJob::dispatch([
            'channel' => 'tiktok',
            'payload' => $payload,
        ])->onQueue('inbound');

        return response('OK', 200);
    }
}
