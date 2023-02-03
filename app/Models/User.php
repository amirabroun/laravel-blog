<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Casts\Attribute,
    SoftDeletes
};
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'email', 'is_admin'];

    protected $appends = ['full_name'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function fullName(): Attribute
    {
        $fullName = $this?->first_name . ' ' . $this?->last_name;

        return Attribute::get(fn () => $fullName);
    }

    public function isAdmin()
    {
        return $this->is_admin == 1;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);

        return $this;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
