<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\{InteractsWithMedia, HasMedia};
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Casts\Attribute,
    SoftDeletes
};
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasUuid;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuid, InteractsWithMedia;

    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'is_admin'];

    protected $appends = ['full_name', 'avatar'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useDisk('media');
    }

    protected function avatar(): Attribute
    {
        if (!$this->relationLoaded('media')) {
            $this->load('media');
        }

        $avatar = $this->media->where('collection_name', 'avatar')->last();

        $url = !$avatar ? null : $avatar->getUrl();

        return Attribute::get(fn () => $url);
    }

    protected function fullName(): Attribute
    {
        $fullName = $this?->first_name . ' ' . $this?->last_name;

        if ($fullName == ' ') {
            $fullName = $this->email;
        }

        return Attribute::get(fn () => $fullName);
    }

    protected function password(): Attribute
    {
        return Attribute::set(
            fn ($password) => Hash::make($password)
        );
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function resume()
    {
        return $this->hasOne(Resume::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isAdmin()
    {
        return $this->is_admin == 1;
    }

    public function ownerOrAdmin($object)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($object->user->id == $this->id) {
            return true;
        }

        return false;
    }

    public function profileOwnerOrAdmin($user)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($user->id == $this->id) {
            return true;
        }

        return false;
    }
}
