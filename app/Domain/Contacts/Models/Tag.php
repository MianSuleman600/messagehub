<?php

namespace App\Domain\Contacts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_tag');
    }
}
