<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserBoardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ListCardController;
use App\Http\Controllers\UserGroupController;
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
        Route::get('/getAvatar', [UserController::class, 'getAvatar']);
        Route::post('/updateAvatar', [UserController::class, 'updateAvatar']);
        Route::post('/searchEmail', [UserController::class, 'searchByEmail']);
    });
});


Route::prefix('board/')->group(function () {
    Route::get('index',[BoardController::class,'index']);
    Route::get('getById/{id}',[BoardController::class,'getById']);
    Route::get('createByMe',[BoardController::class,'createByMe']);
    Route::get('getRole/{id}',[BoardController::class,'getRole']);
    Route::get('getUsers/{id}',[BoardController::class,'getUsers']);
    Route::post('store',[BoardController::class,'store']);
    Route::post('addUser',[UserBoardController::class,'store']);
    Route::delete('delete/{id}',[BoardController::class,'delete']);
});

Route::prefix('group/')->group(function () {
    Route::get('index',[GroupController::class,'index']);
    Route::post('store',[GroupController::class,'store']);
    Route::post('addUser',[UserGroupController::class,'store']);
    Route::delete('delete/{id}',[GroupController::class,'delete']);
    Route::get('getUser/{id}',[UserGroupController::class,'index']);
    Route::put('changeRole/{id}',[UserGroupController::class,'changeRole']);
    Route::get('getRole/{id}',[GroupController::class,'getRole']);
    Route::delete('delete/{id}',[UserGroupController::class,'delete']);
});

Route::prefix('list/')->group(function () {
    Route::post('store',[ListCardController::class,'store']);
    Route::put('update/{id}',[ListCardController::class,'update']);
    Route::get('index/{id}',[ListCardController::class,'index']);
    Route::delete('delete/{id}',[ListCardController::class,'deleteList']);
    Route::put('update/{id}',[ListCardController::class,'update']);
    Route::post('changeSeq',[ListCardController::class,'changeSeq']);
});

Route::prefix('card/')->group(function () {
    Route::post('store',[CardController::class,'store']);
    Route::delete('delete/{id}',[CardController::class,'delete']);
    Route::get('index/{id}',[CardController::class,'index']);
    Route::post('changeSeq',[CardController::class,'changeSeq']);
    Route::post('comment',[CommentController::class,'comment']);
    Route::put('editCmt/{id}',[CommentController::class,'update']);
    Route::delete('deleteComment/{id}',[CommentController::class,'delete']);
    Route::post('update',[CardController::class,'update']);
});

Route::prefix('tag/')->group(function () {
    Route::post('addMember',[TagController::class,'addMember']);
});

