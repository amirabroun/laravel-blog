<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('users')
    ->controller(AuthController::class)
    ->middleware('auth')
    ->group(function () {
        Route::get('/', 'index')->middleware('admin')->name('users.index');
        Route::get('/{id}', 'show')->name('users.profile.show');
        Route::get('/{id}/edit', 'edit')->name('users.profile.edit');
        Route::put('/{id}', 'update')->name('users.profile.update');
    });

Route::prefix('register')
    ->controller(AuthController::class)
    ->middleware('guest')
    ->group(function () {
        Route::view('/', 'auth.register')->name('register.index');
        Route::post('/', 'register')->name('register');
    });

Route::prefix('login')
    ->controller(AuthController::class)
    ->middleware('guest')
    ->group(function () {
        Route::view('/', 'auth.login')->name('login.index');
        Route::post('/', 'login')->name('login');
    });

Route::get('log-out', [AuthController::class, 'logout'])->middleware('auth')->name('log-out');
