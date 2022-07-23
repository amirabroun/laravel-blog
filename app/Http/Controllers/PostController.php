<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{User, File};

class PostController extends Controller
{
    public function index()
    {
        return view('post.newPost');
    }

    public function store(Request $request)
    {
        $post = $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        /** @var User */
        $user = auth()->user();

        $user->posts()->save(new Post([
            'title' => $post['title'],
            'body' => $post['message'],
            'image_url' => $this->saveFile($request->file('image')),
        ]));

        return redirect('/');
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
