<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'percent'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
