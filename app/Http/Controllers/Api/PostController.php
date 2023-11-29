<?php

namespace App\Http\Controllers\Api;

use App\Models\{Post};
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $postData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'exists:categories,id',
            'image' => 'file',
        ]);

        $this->authUser->posts()->save($post = new Post($postData));

        if ($request->file('image', false)) {
            $post->addMediaFromRequest('image')->usingFileName(
                $request->file('image')->hashName()
            )->toMediaCollection('image');
        }

        $post->fresh();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.store_post'),
            'data' => compact('post'),
        ];
    }
}
