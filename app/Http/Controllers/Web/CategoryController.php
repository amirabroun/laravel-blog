<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index($uuid)
    {
        $category = Category::query()->where('uuid', $uuid)->firstOrFail();

        session()->put('activeCategory', $category->id);

        $posts = $category->posts()->with('user')->orderBy('created_at', 'desc')->get();

        return view('index', compact('posts'));
    }

    public function edit($uuid)
    {
        $category = Category::query()->where('uuid', $uuid)->firstOrFail();

        return view('category.editCategory', compact('category'));
    }

    public function update(Request $request, $uuid)
    {
        $category = Category::query()->where('uuid', $uuid)->firstOrFail();

        $newCategory = $request->validate([
            'title' => ['required', Rule::unique('categories', 'title')->ignore($category->id)],
        ]);

        $category->update($newCategory);

        return view('category.editCategory', compact('category'))->with([
            'updateMessage' => 'Category updated successfully'
        ]);
    }

    public function store(Request $request)
    {
        $category = $request->validate([
            'title' => 'required|string|unique:categories,title',
        ]);

        Category::create($category);

        return redirect()->route('posts.index');
    }

    public function destroy($uuid)
    {
        $category = Category::query()->where('uuid', $uuid)->firstOrFail();

        $category->delete();

        return redirect()->route('posts.index');
    }
}
