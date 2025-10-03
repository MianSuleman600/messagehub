<?php

namespace App\Integrations\Channels\Contracts;

interface ChannelClient
{
    /**
     * Send a message via the channel.
     *
     * @param array{to:mixed, body:string, meta?:array} $message
     * @return object{ok:bool, external_id?:string, raw?:mixed}
     */
    public function send(array $message): object;
}
