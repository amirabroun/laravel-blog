<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Blog Routes Part
|--------------------------------------------------------------------------
|
*/

Route::controller(App\Http\Controllers\PostController::class)->group(function () {
    Route::prefix('posts')->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('/create', 'create')->name('posts.create');
            Route::post('/', 'store')->name('posts.store');
            Route::get('/{id}/like', 'like')->name('posts.like');

            Route::middleware('ownerOrAdmin')->prefix('/{id}')->group(function () {
                Route::get('/edit', 'edit')->name('posts.edit');
                Route::put('/', 'update')->name('posts.update');
                Route::delete('/', 'destroy')->name('posts.destroy');
                Route::put('/delete-file', 'deletePostFile')->name('posts.deleteFile');
            });
        });

        Route::get('/{id}', 'show')->name('posts.show');
    });

    Route::get('/', 'index')->name('posts.index');
    Route::get('/categories/{title}/posts',  'index')->name('categories.posts.index');
});

Route::prefix('posts/{post_id}')
    ->middleware('auth')
    ->controller(App\Http\Controllers\CommentController::class)
    ->group(function () {
        Route::post('comments', 'store')->name('posts.comments.store');
        Route::delete('comment/{id}', 'destroy')->name('posts.comments.destroy');
    });

Route::prefix('categories')->controller(App\Http\Controllers\CategoryController::class)->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/create', 'create')->name('categories.create');
        Route::get('/{title}/edit', 'edit')->name('categories.edit');
        Route::put('/{id}', 'update')->name('categories.update');
        Route::delete('/{id}', 'destroy')->name('categories.destroy');
        Route::post('/', 'store')->name('categories.store');
    });

    Route::get('/{title}', 'show')->name('categories.show');
});
