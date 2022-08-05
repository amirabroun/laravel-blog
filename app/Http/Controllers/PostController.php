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

            return view('index')->with(['posts' => $category->posts]);
        }

        return view('index')->with(['posts' => Post::all()]);
    }

    public function create()
    {
        return view('post.newPost');
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

        !$request->file('image') ?:
            $post['image_url'] = $this->saveFile($request->file('image'));


        $this->authUser->posts()->save(new Post($post));

        return redirect('/');
    }

    public function destroy($id)
    {
        $post = Post::query()->find($id);

        !isset($post->image_url) ?: File::delete(public_path('image/' . $post?->image_url));

        !isset($post->labels) ?: $post->labels()->detach($post->labels);

        $post->delete();

        return redirect()->back();
    }

    /**
     * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile|array|null $file
     * @return string $fileName
     */
    private function saveFile($file)
    {
        $fileName = $file->hashName();

        $file->store('image', 'public');

        return $fileName;
    }
}
