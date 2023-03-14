<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\commentController;
use App\Http\Controllers\usersController;

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

// Post Controller route requests
Route::get('/post', [PostController::class, 'index']);
Route::post('/post/store', [PostController::class, 'store']);
Route::post('/post/view/{id}', [PostController::class, 'show']);
Route::post('/post/update/{id}', [PostController::class, 'update']);
Route::delete('/post/delete/{id}', [PostController::class, 'destroy']);

// Users Controller route requests
Route::Post('/signup', [RegisteredUserController::class, 'store']);
Route::Post('/signin', [UsersController::class, 'login']);
Route::Post('/logout', [UsersController::class, 'logout']);

// Comment Controller route requests
Route::post('/comment/store', [commentController::class, 'store']);
Route::get('/comment/view/{id}/', [commentController::class, 'show']);
Route::post('/comment/update/{id}/', [commentController::class, 'update']);

// Reply Controller route requests
Route::post('/reply/store', [replyController::class, 'store']);
Route::post('/reply/update/{id}', [replyController::class, 'update']);

// Admin Controller route requests
Route::post('/admin/store', [adminController::class, 'store']);
Route::get('/admin/show', [postController::class, 'allposttoadmin']);
Route::get('/admin/view/{id}/', [postController::class, 'show']);
Route::post('/admin/update/{id}', [adminController::class, 'update']);
Route::post('/admin/post/approve', [postController::class, 'approve']);
Route::get('/admin/reply', [replyController::class, 'index']);
Route::get('/admin/comment/', [commentController::class, 'index']);
Route::get('/admin/', [usersController::class, 'index']);
Route::post('/admin/make/', [usersController::class, 'makeadmin']);

