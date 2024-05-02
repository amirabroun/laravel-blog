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

    protected $fillable = ['title', 'body'];

    protected $appends = ['image'];

    protected $withCount = ['likes'];

    protected $with = ['media', 'likes'];

    protected $casts = [
        'created_at' => 'date:D F j, Y, G:i',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useDisk('media');
    }

    protected function image(): Attribute
    {
        $image = $this->media->where('collection_name', 'image')->last();

        $url = $image == null ? false : $image->getUrl();

        return Attribute::get(fn () => $url);
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
