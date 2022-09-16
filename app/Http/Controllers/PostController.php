<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class PostController extends Controller
{
    public function index($categoryTitle = null)
    {
        if (URL::current() != route('posts.index') && ($category = Category::query()->where('title', $categoryTitle)->first())) {
            session()->put('activeCategory', $category->id);

            return view('index')->with(['posts' => $category->posts()->with('user')->orderBy('created_at', 'desc')->get()]);
        }

        session()->forget('activeCategory');

        return view('index')->with(['posts' => Post::query()->with('user')->orderBy('created_at', 'desc')->get()]);
    }

    public function create()
    {
        return view('post.newPost');
    }

    public function edit(int $id)
    {
        if (!$post = Post::query()->find($id)) {
            abort(404);
        }

        return view('post.editPost')->with([
            'post' => $post
        ]);
    }

    public function show($id)
    {
        if (!$post = Post::query()->find($id)) {
            abort(404);
        }

        return view('post.singlePost')->with([
            'post' => $post
        ]);
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

        return redirect('/');
    }

    public function update(Request $request, int $id)
    {
        $newPostData = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($file = $request->file('image')) {
            $newPostData['image_url'] = $this->saveFile($file);
        }

        Post::query()->find($id)->update($newPostData);

        return view('post.editPost', [
            'post' => Post::query()->find($id),
            'updateMessage' => 'Post updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $post = Post::query()->find($id);

        $this->deleteFile($post->image_url ?? null);

        !isset($post->labels) ?: $post->labels()->detach($post->labels);

        $post->delete();

        return redirect()->back();
    }

    public function deletePostFile(int|Post $postOrId)
    {
        if (!($postOrId instanceof Post)) {
            $post = Post::query()->find($postOrId);
        }

        if (!isset($post->image_url)) {
            return back()->withErrors(['file' => 'post does not have file']);
        }

        $this->deleteFile($post->image_url);

        $post->update(['image_url' => null]);

        return redirect()->back();
    }

    /**
     * @param $file
     * @return string $fileName
     */
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
