<?php

namespace App\Providers;

use App\Integrations\Channels\ChannelManager;
use Illuminate\Support\ServiceProvider;

class IntegrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ChannelManager::class, function ($app) {
            return new ChannelManager(config('channels.drivers', []));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
