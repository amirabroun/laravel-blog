<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function edit($title)
    {
        if (!$category = Category::query()->where('title', $title)->first()) {
            abort(404);
        }

        return view('category.editCategory')->with([
            'category' => $category
        ]);
    }

    public function update(Request $request, int $id)
    {
        $newCategoryData = $request->validate([
            'title' => ['required', Rule::unique('categories', 'title')->ignore($id)],
            'description' => 'required|string'
        ]);

        Category::query()->find($id)->update($newCategoryData);

        return view('category.editCategory', [
            'category' => Category::query()->find($id),
            'updateMessage' => 'Category updated successfully'
        ]);
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

    public function destroy(int $id)
    {
        $category = Category::query()->find($id);

        $category->delete();

        return redirect(route('posts.index'));
    }
}
