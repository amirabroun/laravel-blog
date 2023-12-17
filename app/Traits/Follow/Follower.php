<?php

namespace App\Traits\Follow;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;
use InvalidArgumentException;

trait Follower
{
    public function follow(Model $followable): array
    {
        if ($followable->is($this)) {
            throw new InvalidArgumentException('Cannot follow yourself.');
        }

        if (!in_array(Followable::class, class_uses($followable))) {
            throw new InvalidArgumentException('The followable model must use the Followable trait.');
        }

        $isPending = $followable->needsToApproveFollowRequests() ?: false;

        $this->followingsRelation()->updateOrCreate([
            'followable_id' => $followable->getKey(),
            'followable_type' => $followable->getMorphClass(),
        ], [
            'accepted_at' => $isPending ? null : now(),
        ]);

        return ['pending' => $isPending];
    }

    public function unfollow(Model $followable): void
    {
        if (!in_array(Followable::class, class_uses($followable))) {
            throw new InvalidArgumentException('The followable model must use the Followable trait.');
        }

        $this->followingsRelation()->of($followable)->get()->each->delete();
    }

    public function toggleFollow(Model $followable): void
    {
        $this->isFollowing($followable) ? $this->unfollow($followable) : $this->follow($followable);
    }

    public function isFollowing(Model $followable): bool
    {
        if (!in_array(Followable::class, class_uses($followable))) {
            throw new InvalidArgumentException('The followable model must use the Followable trait.');
        }

        if ($this->relationLoaded('followings')) {
            return $this->followings
                ->whereNotNull('accepted_at')
                ->where('followable_id', $followable->getKey())
                ->where('followable_type', $followable->getMorphClass())
                ->isNotEmpty();
        }

        return $this->followingsRelation()->of($followable)->accepted()->exists();
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

        return $this->followingsRelation()->of($followable)->notAccepted()->exists();
    }

    public function followingsRelation(): HasMany
    {
        return $this->hasMany(\Overtrue\LaravelFollow\Followable::class, 'follower_id');
    }

    public function followings()
    {
        return $this->morphToMany(
            self::class,
            'followable',
            null,
            'follower_id',
            'followable_id',
        );
    }
    public function approvedFollowings(): HasMany
    {
        return $this->followingsRelation()->accepted();
    }

    public function notApprovedFollowings(): HasMany
    {
        return $this->followingsRelation()->notAccepted();
    }

    public function attachFollowStatus($followables, callable $resolver = null)
    {
        $returnFirst = false;

        switch (true) {
            case $followables instanceof Model:
                $returnFirst = true;
                $followables = collect([$followables]);
                break;
            case $followables instanceof LengthAwarePaginator:
                $followables = $followables->getCollection();
                break;
            case $followables instanceof Paginator:
            case $followables instanceof CursorPaginator:
                $followables = collect($followables->items());
                break;
            case $followables instanceof LazyCollection:
                $followables = collect(iterator_to_array($followables->getIterator()));
                break;
            case is_array($followables):
                $followables = collect($followables);
                break;
        }

        abort_if(!($followables instanceof Enumerable), 422, 'Invalid $followables type.');

        $followed = $this->followingsRelation()->get();

        $followables->map(function ($followable) use ($followed, $resolver) {
            $resolver = $resolver ?? fn ($m) => $m;
            $followable = $resolver($followable);

            if ($followable && in_array(Followable::class, class_uses($followable))) {
                $item = $followed->where('followable_id', $followable->getKey())
                    ->where('followable_type', $followable->getMorphClass())
                    ->first();
                $followable->setAttribute('followed_at', $item ? $item->created_at : null);
                $followable->setAttribute('follow_accepted_at', $item ? $item->accepted_at : null);
            }
        });

        return $returnFirst ? $followables->first() : $followables;
    }
}
