<?php

namespace App\Integrations\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Domain\Messaging\Models\ChannelAccount;
use App\Integrations\Channels\Meta\MetaService;

class MetaConnectController extends Controller
{
    protected MetaService $meta;

    public function __construct(MetaService $meta)
    {
        $this->meta = $meta;
    }

    /**
     * Start the OAuth process
     */
    public function start()
    {
        $url = $this->meta->authorizeUrl();
        return redirect()->away($url);
    }

    /**
     * Callback from Meta OAuth
     */
    public function callback(Request $request)
    {
        $state = $request->get('state');
        if (!$state || $state !== session('meta_oauth_state')) {
            abort(403, 'Invalid OAuth state.');
        }
        session()->forget('meta_oauth_state');

        $code = $request->get('code');
        if (!$code) {
            return redirect()->route('settings.channels')
                ->withErrors(['meta' => 'Authorization failed.']);
        }

        try {
            // Exchange code for access token
            $tokens = $this->meta->exchangeCodeForTokens($code);

            $accessToken = $tokens['access_token'] ?? null;
            $expiresIn   = $tokens['expires_in'] ?? null;
            $userId      = $tokens['user_id'] ?? null;

            if (!$accessToken || !$userId) {
                return redirect()->route('settings.channels')
                    ->withErrors(['meta' => 'Could not obtain Meta tokens.']);
            }

            // Fetch user/page info
            $info = $this->meta->userInfo($accessToken);
            $displayName = $info['name'] ?? 'Meta Account';

            // Save/update channel account in DB
            ChannelAccount::updateOrCreate(
                ['type' => 'messenger', 'external_id' => $userId],
                [
                    'name'        => $displayName,
                    'is_active'   => true,
                    'credentials' => [
                        'provider'     => 'meta',
                        'user_id'      => $userId,
                        'access_token' => $accessToken,
                        'expires_in'   => $expiresIn,
                        'scope'        => $tokens['scope'] ?? null,
                        'token_type'   => $tokens['token_type'] ?? 'Bearer',
                    ],
                ]
            );

            return redirect()->route('settings.channels')
                ->with('status', 'Meta account connected successfully.');
        } catch (\Throwable $e) {
            Log::error('Meta connect failed', ['error' => $e->getMessage()]);
            return redirect()->route('settings.channels')
                ->withErrors(['meta' => 'Meta connect failed. Check app credentials and permissions.']);
        }
    }
}
