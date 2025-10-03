<?php

namespace App\Domain\Messaging\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_account_id', 'type', 'name', 'is_active', 'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function account()
    {
        return $this->belongsTo(ChannelAccount::class, 'channel_account_id');
    }
}
