<?php

namespace App\Domain\Messaging\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoReplyRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel', 'matcher_type', 'pattern', 'reply_template', 'enabled', 'priority',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'priority' => 'integer',
    ];
}
