<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('auth')
    ->middleware('api')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('logout', 'logout');
    });

Route::middleware('api')
    ->controller(CommentController::class)
    ->group(function () {
        Route::get('comments', 'index');
        Route::post('comments', 'store');
    });