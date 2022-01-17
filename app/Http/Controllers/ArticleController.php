<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Author;
use Faker\Generator as Faker;

class ArticleController extends Controller
{
    public function index(Request $request, Faker $faker)
    {
        $this->createArticle($request->input('title'), $request->input('author'), $request->input('body'), $faker);

        return redirect()->route('blog');
    }

    public function createArticle($title, $author, $body, Faker $faker)
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
