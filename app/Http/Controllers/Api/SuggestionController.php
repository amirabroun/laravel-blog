<?php

namespace App\Http\Controllers\Api;

use App\Models\{Post, User};

class SuggestionController extends Controller
{
    public function getSuggestionsUsers()
    {
        $userSuggestionsQuery = auth()->check()
            ? auth()->user()->notFollowed()->take(mt_rand(2, 5))
            : User::query()->take(mt_rand(8, 15));

        $usersNotFollowed = $this->setAuthUserFollowStatus(
            $userSuggestionsQuery->inRandomOrder()->with('media')->get()
        );

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.users'),
            'data' => ['users' => $usersNotFollowed],
        ];
    }

    public function getSuggestionsPosts()
    {
        $posts = Post::query()->when(
            auth()->check(),
            fn ($query) => $query->whereNot('user_id', auth()->id())
        )->with(['user', 'media'])->take(mt_rand(2, 4))->inRandomOrder()->whereHas(
            'user',
            fn ($query) => $query->whereDoesntHave('followers')->when(
                auth()->check(),
                fn ($query) => $query->orWhereHas(
                    'followers',
                    fn ($query) => $query->whereNot('follower_id', auth()->id())
                )
            )
        )->get();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.posts'),
            'data' => compact('posts'),
        ];
    }
}
