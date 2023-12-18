<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Database\Eloquent\Model;

trait Followable
{
    abstract public function needsToApproveFollowRequests();

    public function followersPivot()
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    public function followers()
    {
        return $this->morphToMany(
            User::class,
            'followable',
            'followables',
            'followable_id',
            'follower_id',
        )->withPivot(['accepted_at']);
    }

    public function rejectFollowRequestFrom(Model $follower)
    {
        $this->followersPivot()->followedBy($follower)->get()->each->delete();
    }

    public function acceptFollowRequestFrom(Model $follower)
    {
        $this->followersPivot()->followedBy($follower)->get()->each->update(['accepted_at' => now()]);
    }

    public function isFollowedBy(Model $follower)
    {
        return $this->followersPivot()
            ->whereNotNull('accepted_at')
            ->where('follower_id', $follower->getKey())
            ->exists();
    }

    public function approvedFollowers()
    {
        return $this->followers()->whereNotNull('accepted_at');
    }

    public function notApprovedFollowers()
    {
        return $this->followers()->whereNull('accepted_at');
    }
}
