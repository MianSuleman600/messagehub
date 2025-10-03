<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messaging Channel Drivers
    |--------------------------------------------------------------------------
    |
    | Register your channel clients here. Each driver should implement
    | App\Integrations\Channels\Contracts\ChannelClient.
    |
    */

    'drivers' => [
        'sms' => \App\Integrations\Channels\SMS\TwilioClient::class,
        'whatsapp' => \App\Integrations\Channels\WhatsApp\WhatsAppClient::class,
        // 'messenger' => \App\Integrations\Channels\Messenger\MessengerClient::class,
        // 'instagram' => \App\Integrations\Channels\Instagram\InstagramClient::class,
        // 'email' => \App\Integrations\Channels\Email\EmailClient::class,
    ],

];
