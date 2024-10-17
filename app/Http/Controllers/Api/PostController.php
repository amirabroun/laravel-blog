<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function getExampleData()
    {
        $data = [
            ['id' => 1, 'name' => 'Product 1', 'price' => 51.1, 'description' => 'Description for Product 1'],
            ['id' => 2, 'name' => 'Product 2', 'price' => 52.2, 'description' => 'Description for Product 2'],
            ['id' => 3, 'name' => 'Product 3', 'price' => 53.3, 'description' => 'Description for Product 3'],
            ['id' => 4, 'name' => 'Product 4', 'price' => 54.4, 'description' => 'Description for Product 4'],
            ['id' => 5, 'name' => 'Product 5', 'price' => 55.5, 'description' => 'Description for Product 5'],
            ['id' => 6, 'name' => 'Product 6', 'price' => 56.6, 'description' => 'Description for Product 6'],
            ['id' => 7, 'name' => 'Product 7', 'price' => 57.7, 'description' => 'Description for Product 7'],
            ['id' => 8, 'name' => 'Product 8', 'price' => 58.8, 'description' => 'Description for Product 8'],
            ['id' => 9, 'name' => 'Product 9', 'price' => 59.9, 'description' => 'Description for Product 9'],
            ['id' => 10, 'name' => 'Product 10', 'price' => 61.0, 'description' => 'Description for Product 10'],
            ['id' => 11, 'name' => 'Product 11', 'price' => 62.1, 'description' => 'Description for Product 11'],
            ['id' => 12, 'name' => 'Product 12', 'price' => 63.2, 'description' => 'Description for Product 12'],
            ['id' => 13, 'name' => 'Product 13', 'price' => 64.3, 'description' => 'Description for Product 13'],
            ['id' => 14, 'name' => 'Product 14', 'price' => 65.4, 'description' => 'Description for Product 14'],
            ['id' => 15, 'name' => 'Product 15', 'price' => 66.5, 'description' => 'Description for Product 15'],
            ['id' => 16, 'name' => 'Product 16', 'price' => 67.6, 'description' => 'Description for Product 16'],
            ['id' => 17, 'name' => 'Product 17', 'price' => 68.7, 'description' => 'Description for Product 17'],
            ['id' => 18, 'name' => 'Product 18', 'price' => 69.8, 'description' => 'Description for Product 18'],
            ['id' => 19, 'name' => 'Product 19', 'price' => 70.9, 'description' => 'Description for Product 19'],
            ['id' => 20, 'name' => 'Product 20', 'price' => 72.0, 'description' => 'Description for Product 20'],
            ['id' => 21, 'name' => 'Product 21', 'price' => 73.1, 'description' => 'Description for Product 21'],
            ['id' => 22, 'name' => 'Product 22', 'price' => 74.2, 'description' => 'Description for Product 22'],
            ['id' => 23, 'name' => 'Product 23', 'price' => 75.3, 'description' => 'Description for Product 23'],
            ['id' => 24, 'name' => 'Product 24', 'price' => 76.4, 'description' => 'Description for Product 24'],
            ['id' => 25, 'name' => 'Product 25', 'price' => 77.5, 'description' => 'Description for Product 25'],
            ['id' => 26, 'name' => 'Product 26', 'price' => 78.6, 'description' => 'Description for Product 26'],
            ['id' => 27, 'name' => 'Product 27', 'price' => 79.7, 'description' => 'Description for Product 27'],
            ['id' => 28, 'name' => 'Product 28', 'price' => 80.8, 'description' => 'Description for Product 28'],
            ['id' => 29, 'name' => 'Product 29', 'price' => 81.9, 'description' => 'Description for Product 29'],
            ['id' => 30, 'name' => 'Product 30', 'price' => 83.0, 'description' => 'Description for Product 30']
        ];

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.posts'),
            'data' => ['posts' => $data],
        ];
    }

    public function index()
    {
        $posts = Post::query()->with(['user' => fn($query) => $query->with('media'), 'media'])->orderBy('created_at', 'desc')->whereHas(
            'user',
            fn($query) => $query->whereHas(
                'followers',
                fn($query) => $query->where('follower_id', auth()->id())
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

        $post->media->map(fn($image) => $image->forceDelete());

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.delete_post_image'),
        ];
    }
}
