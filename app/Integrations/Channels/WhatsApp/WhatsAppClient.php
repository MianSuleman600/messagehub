<?php

namespace App\Integrations\Channels\WhatsApp;

use App\Integrations\Channels\Contracts\ChannelClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppClient implements ChannelClient
{
    public function send(array $message): object
    {
        $phone = $message['to']->phone ?? null;
        if (! $phone) {
            return (object)['ok' => false, 'raw' => 'No recipient'];
        }

        $token = config('services.meta.whatsapp_token');
        $phoneId = config('services.meta.whatsapp_phone_id');

        try {
            $resp = Http::withToken($token)
                ->retry(3, 100)
                ->post("https://graph.facebook.com/v18.0/{$phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone,
                    'type' => 'text',
                    'text' => ['body' => $message['body']],
                ]);

            $ok = $resp->successful();

            if (! $ok) {
                Log::warning('WhatsApp message failed', ['response' => $resp->body(), 'payload' => $message]);
            }

            return (object)[
                'ok' => $ok,
                'external_id' => $resp->json('messages.0.id') ?? null,
                'raw' => $resp->json(),
            ];
        } catch (\Throwable $e) {
            Log::error('WhatsApp send exception', ['exception' => $e, 'payload' => $message]);
            return (object)['ok' => false, 'raw' => $e->getMessage()];
        }
    }
}
