<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id', 'image_url', 'category_id'];
    protected $appends = ['count_likes', 'can_auth_user_like_this_post'];
    protected $with = ['user', 'labels', 'comments'];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($created_at) => verta($created_at)->format('Y-n-j , H:i'),
        );
    }

    protected function countLikes(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->likes()->count()
        );
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

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('updated_at', 'desc');
    }

    /**
     * @param User $user
     * @return Post
     */
    public function likePost($user)
    {
        (new Like())
            ->likeable()->associate($this)
            ->user()->associate($user)
            ->save();

        return $this;
    }

    /**
     * @param User $user
     * @return Post
     */
    public function disLikePost($user)
    {
        $user->likes()->whereMorphedTo('likeable', $this)->delete();

        return $this;
    }

    protected function canAuthUserLikeThisPost(): Attribute
    {
        /** @var User */
        if (!$user = auth()->user()) {
            return Attribute::get(fn () => false);  
        }

        return Attribute::make(
            get: fn () => $user
                ->likes()
                ->where('likeable_id', $this->id)
                ->where('likeable_type', Post::class)
                ->count() == 0
        );
    }
}
