<?php

namespace App\Integrations\Channels\SMS;

use App\Integrations\Channels\Contracts\ChannelClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioClient implements ChannelClient
{
    public function send(array $message): object
    {
        $to = $message['to']->phone ?? null;
        if (! $to) {
            return (object)['ok' => false, 'raw' => 'No recipient'];
        }

        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');

        try {
            $resp = Http::asForm()
                ->withBasicAuth($sid, $token)
                ->retry(3, 100)
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'From' => $from,
                    'To' => $to,
                    'Body' => $message['body'],
                ]);

            $ok = $resp->successful();

            if (! $ok) {
                Log::warning('Twilio message failed', ['response' => $resp->body(), 'payload' => $message]);
            }

            return (object)[
                'ok' => $ok,
                'external_id' => $resp->json('sid') ?? null,
                'raw' => $resp->json(),
            ];
        } catch (\Throwable $e) {
            Log::error('Twilio send exception', ['exception' => $e, 'payload' => $message]);
            return (object)['ok' => false, 'raw' => $e->getMessage()];
        }
    }
}
