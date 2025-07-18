<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Sanctum-protected route for getting the authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication routes
Route::prefix('auth')->group(function () {
    // Traditional Authentication
    Route::post('/register', [AuthController::class, 'register']);  // User Registration
    Route::post('/login', [AuthController::class, 'login']);        // Login with email & password
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // Logout

    // Email Verification
    Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify'); // Email verification link
    Route::post('/email/resend', [AuthController::class, 'resendVerification']);  // Resend verification email

    // Password Reset
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);  // Request password reset link
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);   // Reset password with token

    // OAuth2 Authentication
    Route::prefix('{provider}')->middleware('web')->group(function () {
        Route::get('/redirect', [OAuthController::class, 'redirectToProvider']); // Redirect to provider
        Route::get('/callback', [OAuthController::class, 'handleProviderCallback']); // Handle provider callback
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::get('/user/{user}', [UserController::class, 'getUserByIdUser']);
    Route::put('/user', [UserController::class, 'updateUser']);
});

// Protected routes requiring Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    // RESTful routes for PostController using apiResource
    Route::apiResource('posts', PostController::class);
    Route::get('/posts/user/{userId}', [PostController::class, 'getPostByUserId']);
    Route::get('/posts/user/{userId}/count', [PostController::class, 'countPostsByUserId']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/media', [MediaController::class, 'index']);
    Route::post('/media', [MediaController::class, 'createMedia']);
    Route::delete('/media/{post_id}', [MediaController::class, 'deleteByPostId']);
    Route::get('/posts/{post}/media', [MediaController::class, 'getImagesByPostId']);

    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/follow/{followed}', [FollowController::class, 'follow']);
    Route::delete('/unfollow/{followed}', [FollowController::class, 'unfollow']);
    Route::get('/following/{user}', [FollowController::class, 'getFollowing']);
    Route::get('/followingCount/{user}', [FollowController::class, 'getFollowingCount']);
    Route::get('/followers/{user}', [FollowController::class, 'getFollowers']);
    Route::get('/followersCount/{user}', [FollowController::class, 'getFollowersCount']);
    Route::get('/isFollowing/{user}', [FollowController::class, 'isFollowing']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts/{post}/like', [LikeController::class, 'likePost']);
    Route::delete('/posts/{post}/unlike', [LikeController::class, 'unlikePost']);
    Route::get('/posts/{post}/likes', [LikeController::class, 'getLikes']);
    Route::get('/posts/{post}/isLiked', [LikeController::class, 'getIsLiked']);
});
