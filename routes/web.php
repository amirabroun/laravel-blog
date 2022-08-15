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
        Route::middleware(['auth', 'admin'])->group(function () {
            Route::get('/create', 'create')->name('posts.create');
            Route::get('/{id}/edit', 'edit')->name('posts.edit');
            Route::delete('/{id}', 'destroy')->name('posts.destroy');
            Route::post('/', 'store')->name('posts.store');
            Route::put('/{id}', 'update')->name('posts.update');
            Route::put('/{id}/delete-file', 'deletePostFile')->name('posts.deleteFile');
        });

        Route::get('/{id}', 'show')->name('posts.show');
    });

    Route::get('/', 'index')->name('posts.index');
    Route::get('/categories/{category_title}/posts',  'index')->name('categories.posts.index');
});

Route::prefix('categories')->controller(App\Http\Controllers\CategoryController::class)->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/create', 'create')->name('categories.create');
        Route::post('/', 'store')->name('categories.store');
    });

    Route::get('/{title}', 'show')->name('categories.show');
});
