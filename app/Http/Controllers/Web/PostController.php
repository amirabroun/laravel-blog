<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\{Post, Category, Label};

class PostController extends Controller
{
    public function index()
    {
        session()->forget('activeCategory');

        $posts = Post::query()->with(['user', 'media', 'category', 'labels'])->orderBy('created_at', 'desc')->get();

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

    public function toggleLikePost(string $uuid)
    {
        if (!auth()->check()) {
            abort(404);
        }

        if (!$post = Post::query()->with('likes')->where('uuid', $uuid)->first()) {
            abort(404);
        }

        $post = $post->likes->where('user_id', auth()->id())->first() == null
            ? $post->likePost(auth()->user())
            : $post->disLikePost(auth()->user());

        return back();
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
            'label_id' => 'int|exists:labels,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $this->authUser->posts()->save($post = new Post($postData));

        $post->category()->delete();
        $post->category()->associate($request->category_id);

        if ($postData['label_id'] != null) {
            $post->labels()->attach($request->label_id);
        }

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
            'label_id' => 'int|exists:labels,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::query()->where('uuid', $uuid)->with('media')->firstOrFail();
        $post->category()->associate($request->category_id);

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        if ($newPostData['label_id'] != null) {
            $post->labels()->attach($request->label_id);
        }

        if ($request->file('image', false)) {
            $post->addMediaFromRequest('image')->usingFileName(
                $request->file('image')->hashName()
            )->toMediaCollection('image');
        }

        $post->update($newPostData);

        $post = Post::query()->where('uuid', $uuid)->first();
        $categories = Category::all(['id', 'title']);
        $labels = Label::all(['id', 'title']);

        return view('post.editPost', compact('post', 'categories', 'labels'))
            ->with(['updateMessage' => 'Post updated successfully']);
    }

    public function destroy($uuid)
    {
        $post = Post::query()->where('uuid', $uuid)->with('media')->firstOrFail();

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        $post->media->map(fn($image) => $image->forceDelete());

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

        $post->media->map(fn($image) => $image->forceDelete());

        return redirect()->back();
    }
}
