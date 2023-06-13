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
        'contact' => Json::class,
    ];

    protected $hidden = ['id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
