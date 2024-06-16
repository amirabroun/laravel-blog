<?php

use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Blog Routes Part
|--------------------------------------------------------------------------
|
*/

Route::get('/', fn () => view('filterUsers'))->name('users.filter.view');
Route::post('/', [UserController::class, 'filterUsers'])->name('users.filter');
Route::post('export', [UserController::class, 'exportUsers'])->name('users.export');
