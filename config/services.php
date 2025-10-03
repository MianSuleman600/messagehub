<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'twilio' => [
        'sid'   => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from'  => env('TWILIO_FROM'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Meta (WhatsApp / Common)
    |--------------------------------------------------------------------------
    */
    'meta' => [
        'app_id'            => env('META_APP_ID'),
        'app_secret'        => env('META_APP_SECRET'),
        'verify_token'      => env('META_VERIFY_TOKEN'),
        'whatsapp_token'    => env('META_WHATSAPP_TOKEN'),
        'whatsapp_phone_id' => env('META_WHATSAPP_PHONE_ID'),
        'graph_version'     => env('META_GRAPH_VERSION', 'v19.0'),
        'redirect_uri'      => env('APP_URL') . '/settings/connect/meta/callback', // added redirect
    ],

    'whatsapp' => [
        'token'       => env('META_WHATSAPP_TOKEN'),
        'business_id' => env('WHATSAPP_BUSINESS_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Facebook Messenger
    |--------------------------------------------------------------------------
    */
    'facebook' => [
        'app_id'        => env('FB_APP_ID'),
        'app_secret'    => env('FB_APP_SECRET'),
        'page_id'       => env('FB_PAGE_ID'),
        'verify_token'  => env('FB_VERIFY_TOKEN'),
        'access_token'  => env('FB_PAGE_ACCESS_TOKEN'),
        'graph_version' => env('FB_GRAPH_VERSION', 'v19.0'),
        'redirect_uri'  => env('APP_URL') . '/settings/connect/meta/callback', // added redirect
    ],

    /*
    |--------------------------------------------------------------------------
    | Instagram
    |--------------------------------------------------------------------------
    */
    'instagram' => [
        'app_id'        => env('FB_APP_ID'),
        'app_secret'    => env('FB_APP_SECRET'),
        'page_id'       => env('FB_PAGE_ID'),
        'verify_token'  => env('FB_VERIFY_TOKEN'),
        'access_token'  => env('FB_PAGE_ACCESS_TOKEN'),
        'graph_version' => env('FB_GRAPH_VERSION', 'v19.0'),
        'redirect_uri'  => env('APP_URL') . '/settings/connect/meta/callback', // added redirect
    ],

    /*
    |--------------------------------------------------------------------------
    | TikTok
    |--------------------------------------------------------------------------
    */
    'tiktok' => [
        'client_key'    => env('TIKTOK_CLIENT_KEY'),
        'client_secret' => env('TIKTOK_CLIENT_SECRET'),
        'redirect'      => env('TIKTOK_REDIRECT'),
        'scopes'        => env('TIKTOK_SCOPES', 'user.info.basic'),
    ],

];
