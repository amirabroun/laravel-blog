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

Route::get('/', [App\Http\Controllers\PublicController::class, 'index']);

Route::get('/welcome', function () {
    return view('welcome');
});

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::prefix('login')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'login')->name('login');
    });

    Route::get('log-out', 'logout')->name('log-out');
});

Route::prefix('posts')->controller(App\Http\Controllers\PostController::class)->group(function () {
    Route::get('/', 'index');
    Route::delete('/', 'destroy')->name('post.delete');
    Route::post('/', 'store')->name('post.new');
});
