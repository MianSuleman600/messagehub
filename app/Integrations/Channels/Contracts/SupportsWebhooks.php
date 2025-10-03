<?php

namespace App\Integrations\Channels\Contracts;

use Illuminate\Http\Request;

interface SupportsWebhooks
{
    public function verify(Request $request): bool;

    /**
     * Transform inbound request into normalized payload
     *
     * @return array{
     *   channel:string,
     *   external_id?:string,
     *   handle:string,
     *   phone?:string,
     *   body?:string,
     *   received_at:\DateTime|string,
     *   meta?:array
     * }
     */
    public function transformInbound(Request $request): array;
}
