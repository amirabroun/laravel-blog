<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Pishran\LaravelPersianString\HasPersianString;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPersianString;

    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'is_admin'];
    protected $appends = ['full_name'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
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

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($created_at) => verta($created_at)->format('H:i, Y/n/j'),
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }
}
