<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;

// Models & Policies
use App\Domain\Messaging\Models\Conversation;
use App\Domain\Messaging\Policies\ConversationPolicy;

// Events
use App\Domain\Messaging\Events\ConversationAssigned;
use App\Domain\Messaging\Events\MessageReceived;
use App\Domain\Messaging\Events\MessageSent;

// Listeners
use App\Domain\Messaging\Listeners\CreateOrUpdateContactOnIncoming;
use App\Domain\Messaging\Listeners\TriggerAutoReply;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the Conversation policy
        Gate::policy(Conversation::class, ConversationPolicy::class);

        // Event listener registration
        Event::listen(
            MessageReceived::class,
            CreateOrUpdateContactOnIncoming::class
        );

        Event::listen(
            MessageReceived::class,
            TriggerAutoReply::class
        );

        // If you want, you can register other listeners for other events
        // Example:
        // Event::listen(ConversationAssigned::class, SomeOtherListener::class);
        // Event::listen(MessageSent::class, SomeOtherListener::class);
    }
}
