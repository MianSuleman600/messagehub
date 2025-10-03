<?php

namespace App\Http\Middleware;

use App\Integrations\Shared\WebhookVerifier;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookSignature
{
    public function __construct(private WebhookVerifier $verifier) {}

    /**
     * Handle an incoming webhook request.
     */
    public function handle(Request $request, Closure $next, ?string $provider = null): Response
    {
        $isValid = match ($provider) {
            'twilio' => $this->verifier->verifyTwilio($request),
            'meta' => $this->verifier->verifyMeta($request),
            'instagram' => $this->verifier->verifyInstagram($request),
            'email' => $this->verifier->verifyEmail($request),
            'tiktok' => $this->verifier->verifyTikTok($request),
            default => app()->isLocal() || app()->runningUnitTests(),
        };

        if (! $isValid) {
            return response('Invalid signature', 403);
        }

        return $next($request);
    }
}
