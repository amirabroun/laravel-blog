<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthAdminController;

Route::controller(AuthAdminController::class)
    ->group(function () {
        Route::get('login/{key}', 'loginPage')->name('login.index');
        Route::post('admin/login/{key}', 'login')->name('login');
        Route::get('about/{id}', 'show')->name('users.profile.show');
        Route::get('logout', 'logout')->name('logout');
    });
