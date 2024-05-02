<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use App\Traits\Followable;
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
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuid, InteractsWithMedia, Followable;

    protected $fillable = ['first_name', 'last_name', 'username', 'password', 'is_admin'];  

    protected $appends = ['full_name', 'avatar'];

    protected $with = ['media'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function needsToApproveFollowRequests()
    {
        return false;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useDisk('media');
    }

    protected function avatar(): Attribute
    {
        $avatar = $this->media->where('collection_name', 'avatar')->last();

        $url = !$avatar ? null : $avatar->getUrl();

        return Attribute::get(fn () => $url);
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

    public function notFollowed(?Followable $followable = null)
    {
        if ($followable == null) $followable = self::class;

        return $followable::query()->whereNot('id', $this->id)->where(fn ($query) => $query->whereHas(
            'followers',
            fn ($query) => $query->whereNot('follower_id', $this->id)
        )->orWhereDoesntHave('followers'));
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

    public function followingsPivot()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function followings()
    {
        return $this->morphToMany(
            self::class,
            'followable',
            'followables',
            'follower_id',
            'followable_id',
        )->withPivot(['accepted_at', 'created_at']);
    }

    protected function attachFollowStatus(): Attribute
    {
        return Attribute::set(
            fn ($password) => Hash::make($password)
        );
    }

    public function isFollowing(Model $followable): bool
    {
        if (!in_array(Followable::class, \class_uses($followable))) {
            throw new InvalidArgumentException('The followable model must use the Followable trait.');
        }

        if ($this->relationLoaded('followings')) {
            return $this->followings
                ->whereNotNull('accepted_at')
                ->where('followable_id', $followable->getKey())
                ->where('followable_type', $followable->getMorphClass())
                ->isNotEmpty();
        }

        return $this->followingsPivot()
            ->where('followable_id', $followable->getKey())
            ->where('followable_type', $followable->getMorphClass())
            ->whereNotNull('accepted_at')
            ->exists();
    }

    public function hasRequestedToFollow(Model $followable): bool
    {
        if (!in_array(Followable::class, \class_uses($followable))) {
            throw new InvalidArgumentException('The followable model must use the Followable trait.');
        }

        if ($this->relationLoaded('followings')) {
            return $this->followings->whereNull('accepted_at')
                ->where('followable_id', $followable->getKey())
                ->where('followable_type', $followable->getMorphClass())
                ->isNotEmpty();
        }

        return $this->followingsPivot()->of($followable)->notAccepted()->exists();
    }

    public function follow(Model $followable)
    {
        if ($followable->is($this)) {
            throw new InvalidArgumentException('Cannot follow yourself.');
        }

        if (!in_array(Followable::class, \class_uses($followable))) {
            throw new InvalidArgumentException('The followable model must use the Followable trait.');
        }

        $isPending = $followable->needsToApproveFollowRequests() ?: false;

        $this->followingsPivot()->updateOrCreate([
            'followable_id' => $followable->getKey(),
            'followable_type' => $followable->getMorphClass(),
        ], [
            'accepted_at' => $isPending ? null : now(),
        ]);
    }

    public function unfollow(Model $followable): void
    {
        if (!in_array(Followable::class, \class_uses($followable))) {
            throw new InvalidArgumentException('The followable model must use the Followable trait.');
        }

        $this->followingsPivot()
            ->where('followable_id', $followable->getKey())
            ->where('followable_type', $followable->getMorphClass())
            ->delete();
    }
}
