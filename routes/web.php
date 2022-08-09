<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Blog Routes Part
|--------------------------------------------------------------------------
|
*/

Route::controller(App\Http\Controllers\PostController::class)->group(function () {
    Route::get('/', 'index')->name('posts.index');
    Route::get('/categories/{category_title}/posts',  'index')->name('categories.posts.index');

    Route::prefix('posts')->group(function () {
        Route::get('/create', 'create')->name('posts.create')->middleware(['auth', 'admin']);
        Route::get('/{id}', 'show')->name('posts.show');
        Route::delete('/{id}', 'destroy')->name('posts.destroy')->middleware(['auth', 'admin']);
        Route::post('/', 'store')->name('posts.store')->middleware(['auth', 'admin']);
    });
});

Route::prefix('categories')->controller(App\Http\Controllers\CategoryController::class)->group(function () {
    Route::get('/create', 'create')->name('categories.create')->middleware(['auth', 'admin']);
    Route::get('/{title}', 'show')->name('categories.show');
    Route::post('/', 'store')->name('categories.store')->middleware(['auth', 'admin']);
});
