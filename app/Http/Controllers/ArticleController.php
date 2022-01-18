<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Author;
use Faker\Generator as Faker;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all(['title', 'body', 'image_name']);

        return view('blog')->with('articles', $articles);
    }

    public function singleArticle($title)
    {
        $article = Article::query()->where('title', '=', $title)->first();

        $author = Author::find($article->author_id);

        return view('singleArticle')->with([
            'article' => $article,
            'author' => $author
        ]);
    }

    public function createArticle(Request $request, Faker $faker)
    {
        $this->createPost($request->input('title'), $request->input('author'), $request->input('body'), $faker);

        return redirect()->route('blog');
    }

    public function createPost($title, $author, $body, Faker $faker)
    {
        $article = new Article;

        $article->author_id = $this->getAuthorId($author);

        $article->title = $title;

        $article->body = $body;

        $article->image_name = $this->getImageName($title, $faker);

        $article->save();
    }

    public function getImageName($title, Faker $faker)
    {
        return $faker->image(public_path('image'), 640, 480, $title, false);
    }

    public function getAuthorId($name)
    {
        $author_id = Author::where('name', '=', $name)->first();

        if (!$author_id) {
            $author_id =  Author::create([
                'name' => $name
            ])->id;
        } else {
            $author_id = $author_id->id;
        }

        return $author_id;
    }
}
