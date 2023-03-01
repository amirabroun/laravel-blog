<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthAdminController;

Route::controller(AuthAdminController::class)
    ->group(function () {
        Route::get('login/{key}', 'loginPage')->name('login.index');
        Route::post('admin/login/{key}', 'login')->name('login');
        Route::get('about/{uuid}', 'show')->name('users.profile.show');
        Route::get('/{uuid}/edit', 'edit')->name('users.profile.edit');
        Route::put('/{uuid}', 'update')->name('users.profile.update');
        Route::get('logout', 'logout')->name('logout');
    });
