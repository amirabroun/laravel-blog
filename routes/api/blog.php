<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

Route::prefix('users')
    ->controller(UserController::class)
    ->whereUuid('uuid')
    ->group(function () {
        Route::get('/', 'index')
            ->name('users.index');

        Route::get('{uuid}', 'show')
            ->name('users.profile');

        Route::put('{uuid}/update-profile', 'updateUserProfile')
            ->middleware('auth:sanctum')
            ->name('auth.profile.update');

        Route::put('{uuid}/update-resume', 'updateUserResume')
            ->middleware('auth:sanctum')
            ->name('auth.profile.update');
    });
