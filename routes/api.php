<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ListCardController;
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


Route::middleware('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [UserController::class, 'login']);
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/logout', [UserController::class, 'logout']);
        Route::post('/refresh', [UserController::class, 'refresh']);
        Route::get('/user-profile', [UserController::class, 'userProfile']);
        Route::post('/change-pass', [UserController::class, 'changePassWord']);
    });
});


Route::prefix('board/')->group(function () {
    Route::get('index',[BoardController::class,'index']);
    Route::post('store',[BoardController::class,'store']);
    Route::delete('delete/{id}',[BoardController::class,'delete']);
});

Route::prefix('list/')->group(function () {
    Route::post('store',[ListCardController::class,'store']);
    Route::get('index/{id}',[ListCardController::class,'index']);
    Route::post('changeSeq',[ListCardController::class,'changeSeq']);
});

Route::prefix('card/')->group(function () {
    Route::post('store',[CardController::class,'store']);
});
