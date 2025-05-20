<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\postController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Models\Movie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Customer_Controller;

Route::get('/', function () {
    return view('welcome');
});
//check database connection
Route::get('/check', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection is working.';
    } catch (\Exception $e) {
        return 'Could not connect to the database. Please check your configuration.';
    }
});
// By Query
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('create', [UserController::class, 'create']);
Route::get('update/{id}', [UserController::class, 'update']);
Route::get('delete/{id}', [UserController::class, 'delete']);
// By Eloquent
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::get('create-post', [PostController::class, 'create']);

Route::get('/movies', function () {
    $movies = Movie::all();
    return view('movie', ['movies' => $movies]);
})->middleware([EnsureTokenIsValid::class]); // Apply the middleware to this route

Route::get('/customer', [Customer_Controller::class, 'index']);
