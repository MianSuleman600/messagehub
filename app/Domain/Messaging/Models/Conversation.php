<?php

namespace App\Domain\Messaging\Models;

use App\Domain\Contacts\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    // Optional: explicitly define table
    // protected $table = 'conversations';

    protected $fillable = [
        'contact_id',
        'channel',
        'assigned_to',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * The contact associated with this conversation
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * The user assigned to this conversation
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * All messages in this conversation, ordered chronologically
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    /**
     * Scope conversations visible to a given user
     * Admin sees all, staff sees own + unassigned
     */
    public function scopeVisibleTo($query, User $user)
    {
        if ($user->hasRole('Admin')) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            $q->whereNull('assigned_to')
              ->orWhere('assigned_to', $user->id);
        });
    }

    /**
     * Helper to check if a conversation is assigned
     */
    public function isAssigned(): bool
    {
        return !is_null($this->assigned_to);
    }

    /**
     * Helper to check if conversation is assigned to a specific user
     */
    public function isAssignedTo(User $user): bool
    {
        return $this->assigned_to === $user->id;
    }
}
