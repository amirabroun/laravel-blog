<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\IndexController;

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

Route::get('/', [IndexController::class, 'index'])->name('blog');

Route::get('/admin', function () {
    return view('admin');
})->name('admin');


Route::get('/singleArticle/{title}', function ($title = null) {
    return view('singleArticle');
})->name('singleArticle');


Route::get('/article', function () {
    return view('article');
})->name('article');

Route::get('/createArticle', function () {
    return view('createArticle');
})->name('createArticle');

Route::post('/createArticle', [ArticleController::class, 'index'])->name('create');
