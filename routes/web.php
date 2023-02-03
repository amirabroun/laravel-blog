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
    ->group(function () {
        Route::get('/{id}', 'show')->name('posts.show');

        Route::middleware('auth')->group(function () {
            Route::middleware('admin')->group(function () {
                Route::get('/create', 'create')->name('posts.create');
                Route::post('/', 'store')->name('posts.store');
            });

            Route::middleware('ownerOrAdmin')->prefix('/{id}')->group(function () {
                Route::get('/edit', 'edit')->name('posts.edit');
                Route::put('/', 'update')->name('posts.update');
                Route::delete('/', 'destroy')->name('posts.destroy');
                Route::put('/delete-file', 'deletePostFile')->name('posts.deleteFile');
            });
        });
    });

Route::prefix('categories')
    ->controller(CategoryController::class)
    ->group(function () {
        Route::get('/{category:title}', 'show')->name('categories.show');
        Route::get('/{category:title}/posts',  'index')->name('categories.posts.index');

        Route::middleware('auth')
            ->group(function () {
                Route::get('/create', 'create')->name('categories.create');
                Route::get('/{category:title}/edit', 'edit')->name('categories.edit');
                Route::put('/{category}', 'update')->name('categories.update');
                Route::delete('/{category}', 'destroy')->name('categories.destroy');
                Route::post('/', 'store')->name('categories.store');
            });
    });
