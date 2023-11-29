<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\{PostController, CategoryController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('posts')
    ->controller(PostController::class)
    ->group(function () {
        Route::get('/', 'index')->name('posts.index');

        Route::post('/', 'store')
            ->middleware('auth:sanctum')
            ->name('posts.store');
    });

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::prefix('users')
    ->controller(UserController::class)
    ->whereUuid('uuid')
    ->group(function () {
        Route::get('/', 'index')
            ->middleware('auth:sanctum')
            ->name('users.index');

        Route::get('{uuid}/posts', 'getUserPosts')
            ->name('users.posts');

        Route::get('{uuid}', 'show')
            ->name('users.profile');

        Route::put('{uuid}/update-profile', 'updateUserProfile')
            ->middleware('auth:sanctum')
            ->name('auth.profile.update');

        Route::put('{uuid}/update-resume', 'updateUserResume')
            ->middleware('auth:sanctum')
            ->name('auth.profile.update');
    });
