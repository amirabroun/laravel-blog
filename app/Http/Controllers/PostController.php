<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function index($categoryTitle = null)
    {
        if (!$categoryTitle) {
            return view('index')->with([
                'posts' => $posts ?? Post::all(),
                'categories' => $categories ?? Category::all()
            ]);
        }

        $category = Category::query()->where('title', $categoryTitle)->first();

        return view('index')->with([
            'posts' => $category->posts,
            'categories' => $categories ?? Category::all(),
            'activeCategory' => $category->id
        ]);
    }

    public function create()
    {
        return view('post.newPost');
    }

    public function show($id)
    {
        return view('post.singlePost')->with([
            'post' => Post::query()->find($id)
        ]);
    }

    public function store(Request $request)
    {
        $post = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $post['image_url'] = $this->saveFile($request->file('image'));

        $this->authUser->posts()->save(new Post($post));

        return redirect('/');
    }

    public function destroy($id)
    {
        $post = Post::query()->find($id);
        $postCategory = $post->category->title;

        File::delete(public_path('image/' . $post->image_url));

        $post->labels()->detach($post->labels);

        $post->delete();

        return redirect(route('blog.filter.category', ['category_title' => $postCategory]));
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
