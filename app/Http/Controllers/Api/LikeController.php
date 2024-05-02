<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;

class LikeController extends Controller
{
    public function toggleLikePost(string $uuid)
    {
        if (!auth()->check()) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.unauthorized'),
            ];
        }

        if (!$post = Post::query()->with('likes')->where('uuid', $uuid)->first()) {
            return [
                'status' => self::HTTP_STATUS_CODE['not_found'],
                'message' => __('app.post_not_found'),
            ];
        }

        $post = $post->likes->where('user_id', auth()->id())->first() == null
            ? $post->likePost(auth()->user())
            :  $post->disLikePost(auth()->user());

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
        ];
    }
}
