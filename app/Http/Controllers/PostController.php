<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('post.newPost');
    }

    public function store(Request $request)
    {
        //
    }
}
