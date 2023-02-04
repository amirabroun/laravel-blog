<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function index()
    {
        session()->forget('activeCategory');

        $posts = Post::query()->with('user')->orderBy('created_at', 'desc')->get();

        return view('index', compact('posts'));
    }

    public function create()
    {
        return view('post.newPost');
    }

    public function edit($uuid)
    {
        if (!$post = Post::query()->where('uuid', $uuid)->first()) {
            abort(404);
        }

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        return view('post.editPost')->with('post', $post);
    }

    public function show($uuid)
    {
        if (!$post = Post::query()->where('uuid', $uuid)->first()) {
            abort(404);
        }

        return view('post.singlePost')->with('post', $post);
    }

    public function store(Request $request)
    {
        $post = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($file = $request->file('image')) {
            $post['image_url'] = $this->saveFile($file);
        }

        $this->authUser->posts()->save(new Post($post));

        return redirect()->route('posts.index');
    }

    public function update(Request $request, $uuid)
    {
        $newPostData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if (!$post = Post::query()->where('uuid', $uuid)->first()) {
            abort(404);
        }

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        if ($file = $request->file('image')) {
            $newPostData['image_url'] = $this->saveFile($file);
        }

        $post->update($newPostData);

        return view('post.editPost', [
            'post' => Post::query()->where('uuid', $uuid)->first(),
            'updateMessage' => 'Post updated successfully'
        ]);
    }

    public function destroy($uuid)
    {
        if (!$post = Post::query()->where('uuid', $uuid)->first()) {
            abort(404);
        }

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        $this->deleteFile($post->image_url ?? null);

        !isset($post->labels) ?: $post->labels()->detach($post->labels);

        $post->delete();

        return redirect()->back();
    }

    public function deletePostFile($uuid)
    {
        if (!$post = Post::query()->where('uuid', $uuid)->first()) {
            abort(404);
        }

        if (!$this->authUser->ownerOrAdmin($post)) {
            abort(404);
        }

        if (!isset($post->image_url)) {
            return back()->withErrors(['file' => 'post does not have file']);
        }

        $this->deleteFile($post->image_url);

        $post->update(['image_url' => null]);

        return redirect()->back();
    }

    private function saveFile($file)
    {
        $fileName = $file->hashName();

        $file->store('image', 'public');

        return $fileName;
    }

    private function deleteFile($path)
    {
        if (!File::exists(base_path('/public/image/') . $path)) {
            return false;
        }

        return File::delete(public_path('image/' . $path));
    }
}
