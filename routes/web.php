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

Route::view('/', 'index', ['posts' => App\Models\Post::all()]);

Route::view('/welcome', 'welcome');

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::prefix('users')->group(function () {
        Route::view('', 'usersList', ['users' => App\Models\User::all()]);
        Route::get('{id}', 'index')->name('profile');
    });

    Route::prefix('login')->group(function () {
        Route::view('/', 'auth.login');
        Route::post('/', 'login')->name('login');
    });

    Route::prefix('register')->group(function () {
        Route::view('/', 'auth.register');
        Route::post('/', 'register')->name('register');
    });

    Route::get('log-out', 'logout')->name('log-out');
});

Route::prefix('posts')->controller(App\Http\Controllers\PostController::class)->group(function () {
    Route::get('/', 'index');
    Route::delete('/', 'destroy')->name('post.delete');
    Route::post('/', 'store')->name('post.new');
});
