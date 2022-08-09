<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($title)
    {
        if (!$category = Category::query()->where('title', $title)->first()) {
            abort(404);
        }

        return view('category.singleCategory')->with([
            'category' => $category
        ]);
    }

    public function create()
    {
        return view('category. createCategory');
    }

    public function store(Request $request)
    {
        $category = $request->validate([
            'title' => 'required|string|unique:categories,title',
            'description' => 'string'
        ]);

        $newCategory = new Category($category);
        $newCategory->save();

        return redirect(route('posts.index'));
    }
}
