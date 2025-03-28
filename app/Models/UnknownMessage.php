<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnknownMessage extends Model
{
    use HasFactory;

    protected $fillable = ['message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function save(array $options = [])
    {
        if (self::where('message', $this->message)->exists()) {
            return true;
        }

        return parent::save($options);
    }
}
