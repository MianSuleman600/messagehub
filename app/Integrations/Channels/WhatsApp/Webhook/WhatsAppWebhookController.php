<?php

namespace App\Integrations\Channels\WhatsApp\Webhook;

use App\Domain\Messaging\Jobs\ProcessIncomingMessageJob;
use App\Integrations\Shared\WebhookVerifier;
use Illuminate\Http\Request;

class WhatsAppWebhookController
{
    public function __invoke(Request $request, WebhookVerifier $verifier)
    {
        if (! $verifier->verifyMeta($request)) {
            return response('Invalid signature', 403);
        }

        $entries = $request->input('entry', []);

        foreach ($entries as $entry) {
            foreach (($entry['changes'] ?? []) as $change) {
                $msgs = data_get($change, 'value.messages', []);
                foreach ($msgs as $m) {
                    $payload = [
                        'channel' => 'whatsapp',
                        'external_id' => $m['id'] ?? null,
                        'handle' => data_get($change, 'value.contacts.0.wa_id'),
                        'phone' => data_get($change, 'value.contacts.0.wa_id'),
                        'body' => data_get($m, 'text.body'),
                        'received_at' => now(),
                        'meta' => ['meta' => $m],
                    ];
                    dispatch(new ProcessIncomingMessageJob($payload))->onQueue('inbound');
                }
            }
        }

        return response('OK');
    }
}
