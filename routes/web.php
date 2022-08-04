<?php

use Illuminate\Support\Facades\Route;

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

Route::view('/welcome', 'welcome');

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', 'index')->name('users.index');
        Route::get('/{id}', 'show')->name('users.profile.show');
        Route::put('/{id}', 'update')->name('users.profile.update');
    });

    Route::prefix('login')->group(function () {
        Route::view('/', 'auth.login')->name('login.index');
        Route::post('/', 'login')->name('login');
    });

    Route::prefix('register')->group(function () {
        Route::view('/', 'auth.register')->name('register.index');
        Route::post('/', 'register')->name('register');
    });

    Route::get('log-out', 'logout')->name('log-out');
});

Route::controller(App\Http\Controllers\PostController::class)->group(function () {
    Route::get('/', 'index')->name('posts.index');
    Route::get('/categories/{category_title}/posts',  'index')->name('blog.filter.category');

    Route::prefix('posts')->group(function () {
        Route::get('/create', 'create')->name('posts.create');
        Route::get('/{id}', 'show')->name('posts.show');
        Route::delete('/{id}', 'destroy')->name('posts.destroy');
        Route::post('/', 'store')->name('posts.store');
    });
});

Route::prefix('categories')->controller(App\Http\Controllers\CategoryController::class)->group(function () {
    Route::get('/create', 'create')->name('categories.create');
    Route::get('/{title}', 'show')->name('categories.show');
    Route::post('/', 'store')->name('categories.store');
});
