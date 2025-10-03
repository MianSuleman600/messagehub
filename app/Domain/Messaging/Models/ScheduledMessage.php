<?php

namespace App\Domain\Messaging\Models;

use App\Domain\Contacts\Models\Contact;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id', 'contact_id', 'user_id', 'channel', 'body', 'send_at', 'status', 'meta',
    ];

    protected $casts = [
        'send_at' => 'datetime',
        'meta' => 'array',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
