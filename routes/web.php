<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ArticleController::class, 'index'])->name('blog');

Route::get('/blog', function () {
    return redirect()->route('blog');
});

Route::get('/blog/{title}', [ArticleController::class, 'singleArticle']);

Route::get('/author/{name}', [AuthorController::class, 'singleAuthor']);

Route::get('/author', [AuthorController::class, 'index'])->name('author');

Route::get('/article', function () {
    return view('article');
})->name('article');

Route::get('/createArticle', function () {
    return view('createArticle');
})->name('createArticle');

Route::post('/createArticle', [ArticleController::class, 'createArticle'])->name('create');
