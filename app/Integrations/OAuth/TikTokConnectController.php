<?php

namespace App\Integrations\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\Messaging\Models\ChannelAccount;
use Illuminate\Support\Facades\Log;

class TikTokConnectController extends Controller
{
    public function __construct(protected TikTokOAuthService $tiktok) {}

    // Step 1: Redirect user to TikTok OAuth
    public function start()
    {
        $state = $this->tiktok->makeState();
        return redirect()->away($this->tiktok->authorizeUrl($state));
    }

    // Step 2: Handle OAuth callback
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
            $tokens = $this->tiktok->exchangeCodeForTokens($code);

            $accessToken  = $tokens['access_token'] ?? null;
            $refreshToken = $tokens['refresh_token'] ?? null;
            $openId       = $tokens['open_id'] ?? null;

            if (!$accessToken || !$openId) {
                return redirect()->route('settings.channels')
                    ->withErrors(['tiktok' => 'Could not obtain TikTok tokens.']);
            }

            $user = $this->tiktok->userInfo($accessToken);

            // Save or update TikTok account
            ChannelAccount::updateOrCreate(
                ['provider' => 'tiktok', 'external_id' => $openId],
                [
                    'name'        => $user['display_name'] ?? 'TikTok Account',
                    'is_active'   => true,
                    'credentials' => [
                        'access_token'  => $accessToken,
                        'refresh_token' => $refreshToken,
                        'expires_in'    => $tokens['expires_in'] ?? null,
                        'token_type'    => $tokens['token_type'] ?? 'Bearer',
                    ],
                ]
            );

            return redirect()->route('settings.channels')
                ->with('status', 'TikTok account connected successfully.');

        } catch (\Throwable $e) {
            Log::error('TikTok connect failed', ['error' => $e->getMessage()]);
            return redirect()->route('settings.channels')
                ->withErrors(['tiktok' => 'TikTok connect failed.']);
        }
    }
}
