<?php

namespace App\Integrations\Channels\WhatsApp;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected string $graph;
    protected string $token;
    protected string $businessId;

    public function __construct()
    {
        $version = config('services.meta.graph_version', 'v19.0');

        $this->graph      = "https://graph.facebook.com/{$version}";
        $this->token      = config('services.whatsapp.token');        // Permanent system token
        $this->businessId = config('services.whatsapp.business_id');  // WhatsApp Business Account (WABA) ID
    }

    /**
     * List all phone numbers associated with the WABA.
     *
     * @return array
     */
    public function listPhoneNumbers(): array
    {
        if (!$this->token || !$this->businessId) {
            return [];
        }

        $response = Http::withToken($this->token)
            ->get("{$this->graph}/{$this->businessId}/phone_numbers", [
                'fields' => 'id,display_phone_number,verified_name',
                'limit'  => 200,
            ])
            ->throw()
            ->json();

        return $response['data'] ?? [];
    }

    /**
     * Fetch details for a specific phone number ID.
     *
     * @param string $phoneNumberId
     * @return array
     */
    public function numberDetails(string $phoneNumberId): array
    {
        $response = Http::withToken($this->token)
            ->get("{$this->graph}/{$phoneNumberId}", [
                'fields' => 'id,display_phone_number,verified_name',
            ])
            ->throw()
            ->json();

        return $response ?? [];
    }
}
