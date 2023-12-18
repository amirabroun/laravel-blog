<?php

namespace App\Http\Controllers\Api;

use App\Models\{Post, User};

class SuggestionController extends Controller
{
    public function getSuggestionsUsers()
    {
        $users = User::inRandomOrder()
            ->take(5)
            ->with('media')
            ->get();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.users'),
            'data' => ['users' => $this->setAuthUserFollowStatus($users)],
        ];
    }

    public function getSuggestionsPosts()
    {
        $posts = Post::inRandomOrder()
            ->take(rand(1, 5))
            ->with(['user', 'media'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.posts'),
            'data' => compact('posts'),
        ];
    }
}
