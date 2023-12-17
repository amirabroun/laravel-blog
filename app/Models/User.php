<?php

namespace App\Models;

use App\Traits\Follow\Followable;
use App\Traits\Follow\Follower;
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

    use Follower, Followable;

    protected $fillable = ['first_name', 'last_name', 'username', 'password', 'is_admin'];

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

    protected function followedByAuthUser(): Attribute
    {
        /** @var User */
        if (!$user = auth()->user()) {
            return Attribute::get(fn () => null);
        }

        return Attribute::get(fn () => $this->isFollowedBy($user));
    }

    protected function fullName(): Attribute
    {
        $fullName = $this?->first_name . ' ' . $this?->last_name;

        if ($fullName == ' ') {
            $fullName = $this->username;
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
