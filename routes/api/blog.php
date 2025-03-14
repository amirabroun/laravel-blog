<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\{PostController, CategoryController, LikeController, SuggestionController, TelegramController};

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

        Route::put('{uuid}/update', 'update')->name('posts.update');

        Route::delete('{uuid}', 'delete')->name('posts.delete');

        Route::delete('{uuid}/image', 'deletePostImage')->name('posts.image.delete');
    });

Route::put('posts/{uuid}/toggle-like', [LikeController::class, 'toggleLikePost'])->name('posts.like.toggle');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::prefix('users')
    ->controller(UserController::class)
    ->whereUuid('uuid')
    ->group(function () {
        Route::get('/', 'index')->name('users.index');

        Route::get('{uuid}/posts', 'getUserPosts')->name('users.posts');

        Route::post('{uuid}', 'show')->name('users.profile');

        Route::post('{uuid}/username/check', 'checkUniqueUsername')->name('users.profile.username.update');

        Route::put('{uuid}/update-profile', 'updateUserProfile')->name('auth.profile.update');

        Route::post('{uuid}/avatar', 'addUserAvatar')->name('auth.profile.avatar.store');

        Route::delete('{uuid}/avatar', 'deleteUserAvatar')->name('auth.profile.avatar.delete');

        Route::put('{uuid}/toggle-follow', 'toggleFollow')->name('users.follow.toggle');

        Route::put('{uuid}/update-resume', 'updateUserResume')->name('auth.profile.update');

        Route::get('{uuid}/notifications', 'getUserNotifications')->name('users.notifications');
    });

Route::prefix('suggestions')
    ->controller(SuggestionController::class)
    ->group(function () {
        Route::get('users', 'getSuggestionsUsers')->name('users.suggestions.users');

        Route::get('posts', 'getSuggestionsPosts')->name('users.suggestions.posts');
    });

Route::post('telegram/inputs/{token}', TelegramController::class);
