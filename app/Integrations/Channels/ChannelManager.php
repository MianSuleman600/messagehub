<?php

namespace App\Integrations\Channels;

use App\Integrations\Channels\Contracts\ChannelClient;
use InvalidArgumentException;

class ChannelManager
{
    private array $clients = [];

    public function __construct(private array $drivers) {}

    public function clientFor(string $channel): ChannelClient
    {
        if (isset($this->clients[$channel])) {
            return $this->clients[$channel];
        }

        $driver = $this->drivers[$channel] ?? null;
        if (! $driver) {
            throw new InvalidArgumentException("No driver registered for channel [{$channel}]");
        }

        return $this->clients[$channel] = app($driver);
    }
}
