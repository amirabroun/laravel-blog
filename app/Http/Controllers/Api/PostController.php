<?php

namespace App\Http\Controllers\Api;

use App\Models\{Post};

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::query()->with(['user', 'media'])->orderBy('created_at', 'desc')->get();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.posts'),
            'data' => compact('posts'),
        ];
    }
}
