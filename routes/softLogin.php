<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Soft Login Part
|--------------------------------------------------------------------------
|
*/

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::view('/', 'auth.softLogin')->name('softLogin.index');
    Route::post('/', 'softLogin')->name('softLogin');
});
