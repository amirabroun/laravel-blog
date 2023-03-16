<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Post, Category};

class PostController extends Controller
{
    public function index()
    {
        session()->forget('activeCategory');

        $posts = Post::query()->with(['user', 'media'])->orderBy('created_at', 'desc')->get();

        return view('index', compact('posts'));
    }

    public function edit($uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->with('media')->firstOrFail();

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        $categories = Category::all(['id', 'title'])->except(['id' => $post->category_id]);

        return view('post.editPost', compact('post', 'categories'));
    }

    public function show($uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->with(['category', 'media'])->firstOrFail();

        $categories = Category::all(['id', 'title'])->except(['id' => $post->category_id]);

        return view('post.singlePost')->with(compact('post', 'categories'));
    }

    public function store(Request $request)
    {
        $postData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $this->authUser->posts()->save($post = new Post($postData));

        if ($request->file('image', false)) {
            $post->addMediaFromRequest('image')->usingFileName(
                $request->file('image')->hashName()
            )->toMediaCollection('image');
        }

        return redirect()->route('posts.index');
    }

    public function update(Request $request, $uuid)
    {
        $newPostData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::query()->where('uuid', $uuid)->with('media')->firstOrFail();

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        if ($request->file('image', false)) {
            $post->addMediaFromRequest('image')->usingFileName(
                $request->file('image')->hashName()
            )->toMediaCollection('image');
        }

        $post->update($newPostData);

        $post = Post::query()->where('uuid', $uuid)->first();
        $categories = Category::all(['id', 'title'])->except(['id' => $post->category_id]);

        return view('post.editPost', compact('post', 'categories'))
            ->with(['updateMessage' => 'Post updated successfully']);
    }

    public function destroy($uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->with('media')->firstOrFail();

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        $post->media->map(fn ($image) => $image->forceDelete());

        !isset($post->labels) ?: $post->labels()->detach($post->labels);

        $post->delete();

        return redirect()->back();
    }

    public function deletePostFile($uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->with('media')->firstOrFail();

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        if ($post->media->count() == 0) {
            return back()->withErrors(['file' => 'post does not have file']);
        }

        $post->media->map(fn ($image) => $image->forceDelete());

        return redirect()->back();
    }
}
