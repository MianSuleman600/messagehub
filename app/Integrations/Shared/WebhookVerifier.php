<?php

namespace App\Integrations\Shared;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookVerifier
{
    public function verifyTwilio(Request $request): bool
    {
        $signature = $request->header('X-Twilio-Signature');
        $token = config('services.twilio.token');

        if (! $signature || ! $token) return app()->isLocal();

        // For production, replace with Twilio\Security\RequestValidator
        return true;
    }

    public function verifyMeta(Request $request): bool
    {
        $sig = $request->header('X-Hub-Signature') ?? $request->header('X-Hub-Signature-256');
        $secret = config('services.meta.app_secret');

        if (! $sig || ! $secret) return app()->isLocal();

        // For production, compute HMAC of body
        return true;
    }
}
