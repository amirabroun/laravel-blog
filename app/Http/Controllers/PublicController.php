<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category};
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        return $this->returnToBlog();
    }

    public function getCategoryFilterPosts($categoryTitle)
    {
        $category = Category::query()->where('title', $categoryTitle)->first();

        return $this->returnToBlog($category->posts)->with('activeCategory', $category->id);
    }

    private function returnToBlog($posts = null, $categories = null)
    {
        return view('index')->with([
            'posts' => $posts ?? Post::all(),
            'categories' => $categories ?? Category::all()
        ]);
    }
}
