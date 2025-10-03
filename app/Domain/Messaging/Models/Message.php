<?php

namespace App\Domain\Messaging\Models;

use App\Domain\Contacts\Models\Contact;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // Optional: explicitly define table
    // protected $table = 'messages';

    protected $fillable = [
        'conversation_id',
        'contact_id',
        'user_id',
        'channel',
        'external_id',
        'direction',  // 'inbound' or 'outbound'
        'body',
        'status',     // e.g., sent, delivered, failed
        'sent_at',
        'received_at',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'sent_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    /**
     * The conversation this message belongs to
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * The contact associated with this message
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * The user who sent the message (if internal)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope messages ordered latest first
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Helper to check if message is inbound (from contact)
     */
    public function isInbound(): bool
    {
        return $this->direction === 'inbound';
    }

    /**
     * Helper to check if message is outbound (from user)
     */
    public function isOutbound(): bool
    {
        return $this->direction === 'outbound';
    }

    /**
     * Helper to check if message is sent successfully
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }
}
