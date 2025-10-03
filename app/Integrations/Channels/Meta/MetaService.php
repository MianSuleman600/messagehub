<?php

namespace App\Integrations\Channels\Meta;

use Illuminate\Support\Facades\Http;

class MetaService
{
    protected string $appId;
    protected string $appSecret;
    protected string $redirectUri;
    protected string $graph;

    public function __construct()
    {
        $this->appId       = config('services.meta.app_id');
        $this->appSecret   = config('services.meta.app_secret');
        $this->redirectUri = route('oauth.meta.callback', [], true); // HTTPS enforced
        $this->graph       = 'https://graph.facebook.com'; // Graph API base
    }

    /**
     * Generate the OAuth authorization URL
     */
    public function authorizeUrl(): string
    {
        // Generate a secure random state and store in session
        $state = bin2hex(random_bytes(16));
        session(['meta_oauth_state' => $state]);

        $params = http_build_query([
            'client_id'     => $this->appId,
            'redirect_uri'  => $this->redirectUri,
            'state'         => $state,
            'scope'         => 'pages_messaging,pages_show_list,instagram_basic',
            'response_type' => 'code',
        ]);

        // Updated to v23.0 style
        return "https://www.facebook.com/v23.0/dialog/oauth?{$params}";
    }

    /**
     * Exchange code for user access token
     */
    public function exchangeCodeForTokens(string $code): array
    {
        return Http::get("{$this->graph}/v23.0/oauth/access_token", [
            'client_id'     => $this->appId,
            'client_secret' => $this->appSecret,
            'redirect_uri'  => $this->redirectUri,
            'code'          => $code,
        ])->throw()->json();
    }

    /**
     * Fetch user info (basic profile)
     */
    public function userInfo(string $accessToken): array
    {
        return Http::get("{$this->graph}/me", [
            'fields'       => 'id,name',
            'access_token' => $accessToken,
        ])->throw()->json();
    }

    /**
     * Fetch Pages connected to the user
     */
    public function userPages(string $accessToken): array
    {
        return Http::get("{$this->graph}/me/accounts", [
            'access_token' => $accessToken,
        ])->throw()->json();
    }

    /**
     * Fetch Instagram Business Accounts linked to a Page
     */
    public function pageInstagramAccount(string $pageAccessToken, string $pageId): array
    {
        return Http::get("{$this->graph}/{$pageId}", [
            'fields'       => 'instagram_business_account',
            'access_token' => $pageAccessToken,
        ])->throw()->json();
    }
}
