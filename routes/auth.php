<?php

use Illuminate\Support\Facades\Route;

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::middleware('softLogin')->group(function () {
        Route::prefix('users')->middleware('auth')->group(function () {
            Route::get('/', 'index')->name('users.index')->middleware('admin');
            Route::get('/{id}', 'show')->name('users.profile.show');
            Route::get('/{id}/edit', 'edit')->name('users.profile.edit');
            Route::put('/{id}', 'update')->name('users.profile.update')->middleware('admin');
        });

        Route::prefix('register')->group(function () {
            Route::view('/', 'auth.register')->name('register.index');
            Route::post('/', 'register')->name('register');
        });

        Route::get('log-out', 'logout')->name('log-out');
    });

    Route::prefix('login')->group(function () {
        Route::view('/', 'auth.login')->name('login.index');
        Route::post('/', 'login')->name('login');
    });
});
