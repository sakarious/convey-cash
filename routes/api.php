<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;

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

//Prefix all endpoint with v1 - Versioning
Route::group(['prefix' => 'v1'], function(){

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/checkAuth', [AccountController::class, 'checkAuth'])->name('checkAuth');

    Route::post('/verify', [AccountController::class, 'verifyAccount'])->middleware('auth:api');

    Route::post('/transfer', [AccountController::class, 'transfer'])->middleware('auth:api');

    Route::get('/history', [AccountController::class, 'history'])->middleware('auth:api');

});