<?php

namespace App\Integrations\Channels\Instagram\Webhook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Messaging\Jobs\ProcessIncomingMessageJob;
use Illuminate\Support\Facades\Log;

class InstagramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // GET verification
        if ($request->isMethod('GET')) {
            $verifyToken = config('services.meta.verify_token');
            if ($request->get('hub_verify_token') === $verifyToken) {
                return response($request->get('hub_challenge'), 200);
            }
            return response('Invalid verify token', 403);
        }

        // POST: Incoming messages
        $payload = $request->all();
        Log::info('Instagram Webhook received', ['payload' => $payload]);

        ProcessIncomingMessageJob::dispatch([
            'channel' => 'instagram',
            'payload' => $payload,
        ])->onQueue('inbound');

        return response('EVENT_RECEIVED', 200);
    }
}
