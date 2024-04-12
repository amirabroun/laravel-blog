<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::query()->with(['user' => fn ($query) => $query->with('media'), 'media'])->orderBy('created_at', 'desc')->whereHas(
            'user',
            fn ($query) => $query->whereHas(
                'followers',
                fn ($query) => $query->where('follower_id', auth()->id())
            )
        )->orWhereBelongsTo(auth()->user())->get();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.posts'),
            'data' => ['posts' => PostResource::collection($posts)],
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

        auth()->user()->posts()->save($post = new Post($postData));

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

    function delete(string $uuid)
    {
        if (!auth()->check()) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.unauthorized')
            ];
        }

        auth()->user()->posts()->where('uuid', $uuid)->delete();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.delete_post'),
        ];
    }

    public function update(Request $request, $uuid)
    {
        $newPostData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'exists:categories,id',
            'image' => 'file',
        ]);

        if (!$post = Post::query()->where('uuid', $uuid)->with('media')->first()) {
            return [
                'status' => self::HTTP_STATUS_CODE['not_found'],
                'message' => __('app.post_not_found'),
            ];
        }

        if (!auth()->user()->ownerOrAdmin($post)) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.no_update_access')
            ];
        }

        if ($request->file('image', false)) {
            $post->addMediaFromRequest('image')->usingFileName(
                $request->file('image')->hashName()
            )->toMediaCollection('image');
        }

        $post->update($newPostData);
        $post = Post::query()->where('uuid', $uuid)->with('media')->first();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.store_post'),
            'data' => compact('post'),
        ];
    }

    public function deletePostImage($uuid)
    {
        if (!$post = Post::query()->where('uuid', $uuid)->with('media')->first()) {
            return [
                'status' => self::HTTP_STATUS_CODE['not_found'],
                'message' => __('app.post_not_found'),
            ];
        }

        if (!auth()->user()->ownerOrAdmin($post)) {
            return [
                'status' => self::HTTP_STATUS_CODE['unauthorized'],
                'message' => __('auth.no_update_access')
            ];
        }

        $post->media->map(fn ($image) => $image->forceDelete());

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.delete_post_image'),
        ];
    }
}
