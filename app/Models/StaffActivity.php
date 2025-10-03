<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'subject_type', 'subject_id', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    // Optional: relationship to the user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optional: polymorphic relation to the subject of the activity
    public function subject()
    {
        return $this->morphTo();
    }
}
