<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::prefix('login')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'login')->name('login');
    });

    Route::prefix('register')->group(function () {
        Route::get('/', 'index')->name('register');
    });
});
