<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('users')
    ->controller(AuthController::class)
    ->middleware('auth')
    ->whereUuid('uuid')
    ->group(function () {
        Route::get('/', 'index')->name('users.index');
        Route::get('{uuid}', 'show')->withoutMiddleware('auth')->name('users.profile.show');
        Route::get('{uuid}/edit', 'edit')->name('users.profile.edit');
        Route::put('{uuid}', 'update')->name('users.profile.update');
        Route::delete('{uuid}/delete-avatar', 'deleteUserAvatar')->name('users.files.avatar.delete');
    });

Route::prefix('register')
    ->controller(AuthController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('/', fn () => view('auth.register'))->name('register.index');
        Route::post('/', 'register')->name('register');
    });

Route::prefix('login')
    ->controller(AuthController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('/', fn () => view('auth.login'))->name('login.index');
        Route::post('/', 'login')->name('login');
    });

Route::get('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
