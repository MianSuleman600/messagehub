<?php

namespace App\Integrations\Shared;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedWebhook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'provider',    // Name of the service/provider
        'payload',     // Webhook payload (JSON stored as array)
        'headers',     // Webhook headers (JSON stored as array)
        'status',      // HTTP status code or internal status
        'error',       // Error message or exception
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'payload' => 'array',  // Automatically cast JSON to array
        'headers' => 'array',  // Automatically cast JSON to array
    ];

    /**
     * Optional: log human-readable description of the webhook failure.
     */
    public function description(): string
    {
        return sprintf(
            "[%s] %s failed with status %s: %s",
            now()->toDateTimeString(),
            $this->provider,
            $this->status ?? 'N/A',
            $this->error ?? 'Unknown error'
        );
    }
}
