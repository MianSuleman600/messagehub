<?php

use Illuminate\Support\Facades\Route;
use App\Integrations\Channels\SMS\Webhook\TwilioWebhookController;
use App\Integrations\Channels\WhatsApp\Webhook\WhatsAppWebhookController;
use App\Integrations\Channels\Messenger\Webhook\MessengerWebhookController;
use App\Integrations\Channels\Instagram\Webhook\InstagramWebhookController;
use App\Integrations\Channels\Email\Webhook\EmailWebhookController;
use App\Integrations\OAuth\TikTok\TikTokWebhookController;

/*
|--------------------------------------------------------------------------
| Webhook Routes
|--------------------------------------------------------------------------
| Routes for external services (Messenger, Instagram, WhatsApp, Twilio, Email, TikTok)
| CSRF is excluded via 'verify.webhook' middleware.
*/

// Twilio SMS
Route::post('/webhooks/twilio', TwilioWebhookController::class)
    ->middleware('verify.webhook:twilio')
    ->name('webhooks.twilio');

// WhatsApp
Route::match(['GET', 'POST'], '/webhooks/whatsapp', [WhatsAppWebhookController::class, 'handle'])
    ->middleware('verify.webhook:meta')
    ->name('webhooks.whatsapp');

// Messenger
Route::match(['GET', 'POST'], '/webhooks/messenger', [MessengerWebhookController::class, 'handle'])
    ->middleware('verify.webhook:meta')
    ->name('webhooks.messenger');

// Instagram
Route::match(['GET', 'POST'], '/webhooks/instagram', [InstagramWebhookController::class, 'handle'])
    ->middleware('verify.webhook:instagram')
    ->name('webhooks.instagram');

// Email
Route::post('/webhooks/email', [EmailWebhookController::class, 'handle'])
    ->middleware('verify.webhook:email')
    ->name('webhooks.email');

// TikTok
Route::post('/webhooks/tiktok', [TikTokWebhookController::class, 'handle'])
    ->middleware('verify.webhook:tiktok')
    ->name('webhooks.tiktok');
