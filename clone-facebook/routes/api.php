<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'login']);


Route::group(
    ['middleware' => 'auth:api'],
    function ($router) {
        Route::post('/update', [AuthController::class, 'updateUserdata']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::delete('/delete', [AuthController::class, 'deleteUser']);

        Route::get('/posts', [PostController::class, 'index']);
        Route::post('/post/create', [PostController::class, 'storePost']);
        Route::get('/post/{id}', [PostController::class, 'showPostByID']);
        Route::get('/post/update/{id}', [PostController::class, 'updatePostByID']);
        Route::delete('/post/delete/{id}', [PostController::class, 'destroy']);

        Route::get('/likes/{id}', [LikeController::class, 'getLikeByPostID']);
        Route::post('/like/{id}', [LikeController::class, 'LikednDislikedByPostID']);

        Route::get('/comments/{id}', [CommentController::class, 'getAllCommentByPostID']);
        Route::post('/comment/create/{id}', [CommentController::class, 'storeCommentByPostID']);
        Route::get('/comment/update/{id}', [CommentController::class, 'updateCommentByID']);
        Route::delete('/comment/delete/{id}', [CommentController::class, 'destroy']);
    }

);
