<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $articles = Article::all(['id', 'title', 'body', 'image_name']);

        return view('blog')->with('articles', $articles);
    }
}
