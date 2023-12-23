<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;

class SuggestionController extends Controller
{
    public function getSuggestionsUsers()
    {
        $usersNotFollowed = $this->authUser->notFollowed()->inRandomOrder()
            ->take(mt_rand(2, 5))->with('media')->get();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.users'),
            'data' => ['users' => $this->setAuthUserFollowStatus($usersNotFollowed)],
        ];
    }

    public function getSuggestionsPosts()
    {
        $posts = Post::query()->whereNot('user_id', $this->authUser->id)->with(['user', 'media'])->take(mt_rand(2, 4))->inRandomOrder()->whereHas(
            'user',
            fn ($query) => $query->whereDoesntHave('followers')->orWhereHas(
                'followers',
                fn ($query) => $query->whereNot('follower_id', $this->authUser->id)
            )
        )->get();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.posts'),
            'data' => compact('posts'),
        ];
    }
}
