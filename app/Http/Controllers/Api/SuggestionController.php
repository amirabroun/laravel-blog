<?php

namespace App\Http\Controllers\Api;

use App\Models\{Post, User};
use App\Http\Resources\{PostResource, UserResource};

class SuggestionController extends Controller
{
    public function getSuggestionsUsers()
    {
        $userSuggestionsQuery = User::query()->take(mt_rand(2, 5))->inRandomOrder()->with('media');

        !auth()->check() ?:
            $userSuggestionsQuery->whereNotIn('id', [auth()->id(), ...app()->auth_followings->pluck('id')->toArray()]);

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.users'),
            'data' => ['users' => UserResource::collection($userSuggestionsQuery->get())],
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
            'data' => ['posts' => PostResource::collection($posts)],
        ];
    }
}
