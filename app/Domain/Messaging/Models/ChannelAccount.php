<?php

namespace App\Domain\Messaging\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'external_id',   // TikTok open_id or other channel ID
        'name',
        'credentials',
        'meta',
        'webhook_secret',
        'is_active',
    ];

    protected $casts = [
        'credentials' => 'array',
        'meta' => 'array',
        'is_active' => 'boolean',
    ];

    public function channels()
    {
        return $this->hasMany(Channel::class);
    }
}
