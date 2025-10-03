<?php

namespace App\Integrations\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Domain\Messaging\Models\ChannelAccount;

class TikTokConnectController extends Controller
{
    public function __construct(protected TikTokOAuthService $tiktok) {}

    /**
     * Redirect the user to TikTok's OAuth authorization page.
     */
    public function start()
    {
        $state = $this->tiktok->makeState();
        return redirect()->away($this->tiktok->authorizeUrl($state));
    }

    /**
     * Handle the TikTok OAuth callback.
     */
    public function callback(Request $request)
    {
        $state = $request->get('state');
        if (!$state || $state !== session('tiktok_oauth_state')) {
            abort(403, 'Invalid OAuth state.');
        }

        session()->forget('tiktok_oauth_state');

        $code = $request->get('code');
        if (!$code) {
            return redirect()->route('settings.channels')
                ->withErrors(['tiktok' => 'Authorization failed.']);
        }

        try {
            // Exchange code for tokens
            $tokens = $this->tiktok->exchangeCodeForTokens($code);
            $accessToken  = $tokens['access_token'] ?? null;
            $refreshToken = $tokens['refresh_token'] ?? null;
            $openId       = $tokens['open_id'] ?? null;
            $expiresIn    = $tokens['expires_in'] ?? null;

            if (!$accessToken || !$openId) {
                return redirect()->route('settings.channels')
                    ->withErrors(['tiktok' => 'Could not obtain TikTok tokens.']);
            }

            // Fetch user info
            $user = $this->tiktok->userInfo($accessToken);
            $displayName = $user['display_name'] ?? 'TikTok Account';

            // Store or update channel account
            ChannelAccount::updateOrCreate(
                ['type' => 'tiktok', 'external_id' => $openId],
                [
                    'name'        => $displayName,
                    'is_active'   => true,
                    'credentials' => [
                        'provider'      => 'tiktok',
                        'open_id'       => $openId,
                        'access_token'  => $accessToken,
                        'refresh_token' => $refreshToken,
                        'expires_in'    => $expiresIn,
                        'scopes'        => $tokens['scope'] ?? null,
                        'token_type'    => $tokens['token_type'] ?? 'Bearer',
                    ],
                ]
            );

            return redirect()->route('settings.channels')
                ->with('status', 'TikTok account connected successfully.');

        } catch (\Throwable $e) {
            Log::error('TikTok connect failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('settings.channels')
                ->withErrors(['tiktok' => 'TikTok connect failed. Check app credentials and permissions.']);
        }
    }
}
