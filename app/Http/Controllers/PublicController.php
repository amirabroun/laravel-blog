<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category};
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        return view('index')->with([
            'posts' => Post::all(),
            'categories' => Category::all()
        ]);
    }
}
