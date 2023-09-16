<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()->get();

        return [
            'status' => self::HTTP_STATUS_CODE['success'],
            'message' => __('app.categories'),
            'data' => compact('categories'),
        ];
    }
}
