<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();

        return view('author')->with('authors', $authors);
    }

    public function singleAuthor($name)
    {
        $author = Author::query()->where('name', '=', $name)->first();

        return view('singleAuthor')->with('author', $author);
    }

    public function updateAuthor(Request $request)
    {
        
    }
}
