<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::get('account', 'account')
            ->middleware('auth:sanctum')
            ->name('auth.profile');

        Route::post('login', 'login')
            ->name('auth.login');

        Route::post('register', 'register')
            ->name('auth.register');

        Route::get('logout', 'logout')
            ->middleware('auth:sanctum')
            ->name('logout');
    });
