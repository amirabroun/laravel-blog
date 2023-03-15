<?php

use App\Http\Controllers\{CategoryController, PostController};
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
    ->group(function () {
        Route::get('{uuid}', 'show')->withoutMiddleware('auth')->name('posts.show');
        Route::get('create', 'create')->name('posts.create');
        Route::get('{uuid}/edit', 'edit')->name('posts.edit');
        Route::post('/', 'store')->name('posts.store');
        Route::put('{uuid}', 'update')->name('posts.update');
        Route::delete('{uuid}', 'destroy')->name('posts.destroy');
        Route::delete('{uuid}/delete-file', 'deletePostFile')->name('posts.files.delete');
    });

Route::prefix('categories')
    ->controller(CategoryController::class)
    ->middleware('auth')
    ->group(function () {
        Route::get('{uuid}/posts', 'index')->withoutMiddleware('auth')->name('categories.posts');
        Route::get('create', 'create')->name('categories.create');
        Route::get('{uuid}/edit', 'edit')->name('categories.edit');
        Route::put('{uuid}', 'update')->name('categories.update');
        Route::delete('{uuid}', 'destroy')->name('categories.destroy');
        Route::post('/', 'store')->name('categories.store');
    });
