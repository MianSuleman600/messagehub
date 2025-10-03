<?php

namespace App\Integrations\Channels\Email\Webhook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Messaging\Jobs\ProcessIncomingMessageJob;
use Illuminate\Support\Facades\Log;

class EmailWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Email Webhook received', ['payload' => $payload]);

        ProcessIncomingMessageJob::dispatch([
            'channel' => 'email',
            'payload' => $payload,
        ])->onQueue('inbound');

        return response('OK', 200);
    }
}
