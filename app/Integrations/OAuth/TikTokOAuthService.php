<?php

namespace App\Integrations\OAuth;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TikTokOAuthService
{
    protected string $clientKey;
    protected string $clientSecret;
    protected string $redirectUri;
    protected array $scopes;

    public function __construct()
    {
        $this->clientKey    = config('services.tiktok.client_key');
        $this->clientSecret = config('services.tiktok.client_secret');
        $this->redirectUri  = config('services.tiktok.redirect') ?: route('oauth.tiktok.callback');
        $this->scopes       = array_filter(array_map('trim', explode(',', config('services.tiktok.scopes', 'user.info.basic'))));
    }

    /**
     * Generate a random state string and store in session.
     */
    public function makeState(): string
    {
        $state = Str::random(40);
        session(['tiktok_oauth_state' => $state]);
        return $state;
    }

    /**
     * Get TikTok OAuth authorization URL.
     */
    public function authorizeUrl(string $state): string
    {
        $base = 'https://www.tiktok.com/v2/auth/authorize/';
        $params = [
            'client_key'    => $this->clientKey,
            'scope'         => implode(',', $this->scopes),
            'response_type' => 'code',
            'redirect_uri'  => $this->redirectUri,
            'state'         => $state,
        ];

        return $base . '?' . http_build_query($params);
    }

    /**
     * Exchange authorization code for access and refresh tokens.
     */
    public function exchangeCodeForTokens(string $code): array
    {
        $response = Http::asForm()->post('https://open.tiktokapis.com/v2/oauth/token/', [
            'client_key'    => $this->clientKey,
            'client_secret' => $this->clientSecret,
            'code'          => $code,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->redirectUri,
        ])->throw()->json();

        return $response; // access_token, refresh_token, open_id, expires_in, scope
    }

    /**
     * Refresh access token using a refresh token.
     */
    public function refresh(string $refreshToken): array
    {
        $response = Http::asForm()->post('https://open.tiktokapis.com/v2/oauth/token/', [
            'client_key'    => $this->clientKey,
            'client_secret' => $this->clientSecret,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
        ])->throw()->json();

        return $response;
    }

    /**
     * Fetch user info from TikTok API.
     */
    public function userInfo(string $accessToken, array $fields = ['open_id', 'display_name', 'avatar_url']): array
    {
        $response = Http::withToken($accessToken)
            ->get('https://open.tiktokapis.com/v2/user/info/', [
                'fields' => implode(',', $fields),
            ])->throw()->json();

        return $response['data']['user'] ?? [];
    }
}
