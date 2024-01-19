<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\{PostController, CategoryController, SuggestionController};

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

        Route::post('/', 'store')->name('posts.store');
    });

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::prefix('users')
    ->controller(UserController::class)
    ->whereUuid('uuid')
    ->group(function () {
        Route::get('/', 'index')->name('users.index');

        Route::get('{uuid}/posts', 'getUserPosts')->name('users.posts');

        Route::post('{uuid}', 'show')->name('users.profile');

        Route::put('{uuid}/update-profile', 'updateUserProfile')->name('auth.profile.update');

        Route::put('{uuid}/toggle-follow', 'toggleFollow')->name('auth.users.toggleFollow');

        Route::put('{uuid}/update-resume', 'updateUserResume')->name('auth.profile.update');
    });

Route::prefix('suggestions')
    ->controller(SuggestionController::class)
    ->group(function () {
        Route::get('users', 'getSuggestionsUsers')->name('users.suggestions.users');

        Route::get('posts', 'getSuggestionsPosts')->name('users.suggestions.posts');
    });
