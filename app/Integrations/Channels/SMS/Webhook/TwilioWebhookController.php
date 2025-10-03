<?php

namespace App\Integrations\Channels\SMS\Webhook;

use App\Domain\Messaging\Jobs\ProcessIncomingMessageJob;
use App\Integrations\Shared\WebhookVerifier;
use Illuminate\Http\Request;

class TwilioWebhookController
{
    public function __invoke(Request $request, WebhookVerifier $verifier)
    {
        if (! $verifier->verifyTwilio($request)) {
            return response('Invalid signature', 403);
        }

        $payload = [
            'channel' => 'sms',
            'external_id' => $request->input('MessageSid'),
            'handle' => $request->input('From'),
            'phone' => $request->input('From'),
            'body' => $request->input('Body'),
            'received_at' => now(),
            'meta' => ['twilio' => $request->all()],
        ];

        dispatch(new ProcessIncomingMessageJob($payload))->onQueue('inbound');

        return response('OK');
    }
}
