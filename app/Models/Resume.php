<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};

class Resume extends Model
{
    use HasFactory;

    protected $fillable = ['summary', 'experiences', 'education', 'skills'];

    protected $casts = [
        'experiences' => Json::class,
        'education' => Json::class,
        'skills' => Json::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
