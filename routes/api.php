<?php

use App\Http\Controllers\ApiAuthorController;
use App\Http\Controllers\ApiArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* author */
Route::get('/authors', [ApiAuthorController::class, 'index']);

Route::post('/author', [ApiAuthorController::class, 'store']);

Route::get('/author/{id}', [ApiAuthorController::class, 'show']);

Route::put('/author/{id}', [ApiAuthorController::class, 'update']);

Route::post('/author/{id}', [ApiAuthorController::class, 'destroy']);

Route::get('/author/search/{name}', [ApiAuthorController::class, 'search']);

/* article */
Route::get('/articles', [ApiArticleController::class, 'index']);

Route::post('/article', [ApiArticleController::class, 'store']);

Route::get('/article/{title}', [ApiArticleController::class, 'show']);

Route::put('/article/{title}', [ApiArticleController::class, 'update']);

Route::post('/article/{title}', [ApiArticleController::class, 'destroy']);
