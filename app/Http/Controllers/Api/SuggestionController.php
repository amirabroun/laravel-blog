<?php

namespace App\Http\Controllers\Api;

use App\Models\{Post, User};
use App\Http\Resources\{UserCollection, PostCollection};

class SuggestionController extends Controller
{
    public function getSuggestionsUsers()
    {
        $userSuggestionsQuery = auth()->check()
            ? auth()->user()->notFollowed()->take(mt_rand(2, 5))
            : User::query()->take(mt_rand(8, 15));

        $usersNotFollowed = $userSuggestionsQuery->inRandomOrder()->with('media')->get();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.users'),
            'data' => ['users' => UserCollection::make($usersNotFollowed)],
        ];
    }

    public function getSuggestionsPosts()
    {
        $posts = Post::query()->when(
            auth()->check(),
            fn ($query) => $query->whereNot('user_id', auth()->id())
        )->with(['user' => fn ($query) => $query->with('media'), 'media'])->take(mt_rand(2, 4))->inRandomOrder()->whereHas(
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
            'data' => ['posts' => PostCollection::make($posts)],
        ];
    }
}
