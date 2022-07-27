<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryPage($title)
    {
        return view('category.singleCategory')->with([
            'category' => Category::query()->where('title', $title)->first()
        ]);
    }
}
