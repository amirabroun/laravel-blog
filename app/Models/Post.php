<?php

namespace App\Models;

use App\Traits\HasUuid;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};
use Illuminate\Database\Eloquent\{
    Model,
    Casts\Attribute,
    Factories\HasFactory,
};

class Post extends Model implements HasMedia
{
    use HasFactory, HasUuid, InteractsWithMedia;

    protected $fillable = ['title', 'body', 'user_id', 'image_url', 'category_id'];

    protected $appends = ['count_likes', 'can_auth_user_like_this_post'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->useDisk('media');
    }

    protected function countLikes(): Attribute
    {
        return Attribute::get(fn () => $this->likes()->count());
    }

    protected function canAuthUserLikeThisPost(): Attribute
    {
        /** @var User */
        if (!$user = auth()->user()) {
            return Attribute::get(fn () => false);
        }

        $get = $user->likes()
            ->where('likeable_id', $this->id)
            ->where('likeable_type', Post::class)
            ->count() == 0;

        return Attribute::get(fn () => $get);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @return Post
     */
    public function likePost(User $user)
    {
        $newLike = new Like;

        $newLike->likeable()->associate($this)
            ->user()->associate($user)
            ->save();

        return $this;
    }

    /**
     * @return Post
     */
    public function disLikePost(User $user)
    {
        $user->likes()->whereMorphedTo('likeable', $this)->delete();

        return $this;
    }
}
