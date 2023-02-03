<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};

class Like extends Model
{
    use HasFactory;

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likeable()
    {
        return $this->morphTo();
    }
}
