<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public routes
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);
Route::get('/comments', [CommentController::class, 'index']);
Route::get('/comments/{comment}', [CommentController::class, 'show']);

// Public post-specific routes
Route::get('/posts/{post}/comments', [CommentController::class, 'postComments']);
Route::get('/comments/{comment}/replies', [CommentController::class, 'commentReplies']);

// Public user-specific routes
Route::get('/users/{user}/posts', [PostController::class, 'userPosts']);
Route::get('/users/{user}/posts/active', [PostController::class, 'userActivePosts']);
Route::get('/users/{user}/comments', [CommentController::class, 'userComments']);

Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);

    // User management
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);

    // Posts CRUD
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    // Comments CRUD
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'storeForPost']);
    Route::post('/comments/{comment}/replies', [CommentController::class, 'storeReply']);

    // User's own resources
    Route::get('/my/posts', [PostController::class, 'myPosts']);
    Route::get('/my/comments', [CommentController::class, 'myComments']);
});
