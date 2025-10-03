<?php

namespace App\Domain\Messaging\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'name',
        'credentials',
        'meta',
        'webhook_secret',
        'is_active',
    ];

    protected $casts = [
        'credentials' => 'array', // JSON array, not encrypted
        'meta' => 'array',
        'is_active' => 'boolean',
    ];

    public function channels()
    {
        return $this->hasMany(Channel::class);
    }
}
