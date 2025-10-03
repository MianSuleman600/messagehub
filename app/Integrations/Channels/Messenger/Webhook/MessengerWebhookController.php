<?php

namespace App\Integrations\Channels\Messenger\Webhook;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessengerWebhookController
{
    public function handle(Request $request)
    {
        // Messenger verification challenge
        if ($request->isMethod('GET')) {
            $mode      = $request->get('hub_mode');
            $token     = $request->get('hub_verify_token');
            $challenge = $request->get('hub_challenge');

            if ($mode === 'subscribe' && $token === config('services.meta.verify_token')) {
                return response($challenge, 200);
            }

            return response('Invalid verify token', 403);
        }

        // POST: incoming messages
        $payload = $request->all();
        Log::info('Messenger Webhook payload:', $payload);

        // TODO: Dispatch your job to process messages
        // dispatch(new ProcessIncomingMessageJob($payload));

        return response('EVENT_RECEIVED', 200);
    }
}
