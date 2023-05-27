<?php

use App\Http\Controllers\Web\{CategoryController, PostController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Blog Routes Part
|--------------------------------------------------------------------------
|
*/

Route::get('/', [PostController::class, 'index'])->name('posts.index');

Route::prefix('posts')
    ->controller(PostController::class)
    ->middleware('auth')
    ->whereUuid('uuid')
    ->group(function () {
        Route::get('{uuid}', 'show')->withoutMiddleware('auth')->name('posts.show');
        Route::get('{uuid}/edit', 'edit')->name('posts.edit');
        Route::put('{uuid}', 'update')->name('posts.update');
        Route::delete('{uuid}', 'destroy')->name('posts.destroy');
        Route::delete('{uuid}/delete-file', 'deletePostFile')->name('posts.files.delete');
        Route::get('create',  fn () => view('post.newPost'))->name('posts.create');
        Route::post('/', 'store')->name('posts.store');
    });

Route::prefix('categories')
    ->controller(CategoryController::class)
    ->middleware('auth')
    ->whereUuid('uuid')
    ->group(function () {
        Route::get('{uuid}/posts', 'index')->withoutMiddleware('auth')->name('categories.posts');
        Route::get('{uuid}/edit', 'edit')->name('categories.edit');
        Route::put('{uuid}', 'update')->name('categories.update');
        Route::delete('{uuid}', 'destroy')->name('categories.destroy');
        Route::get('create', fn () => view('category.createCategory'))->name('categories.create');
        Route::post('/', 'store')->name('categories.store');
    });
