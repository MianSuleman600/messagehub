<?php

namespace App\Domain\Contacts\Models;

use App\Domain\Messaging\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    // Optional: explicitly define table if needed
    // protected $table = 'contacts';

    protected $fillable = [
        'name',
        'handle',
        'email',
        'phone',
        'meta',
        'last_seen_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'last_seen_at' => 'datetime',
    ];

    // Automatically eager load conversations if desired
    // protected $with = ['conversations'];

    /**
     * Get all conversations for the contact, ordered by last message desc
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class)
                    ->orderByDesc('last_message_at');
    }

    /**
     * Scope to get online contacts (example usage)
     */
    public function scopeOnline($query)
    {
        return $query->where('last_seen_at', '>=', now()->subMinutes(5));
    }
}
