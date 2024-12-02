<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OAuthController;
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
    Route::prefix('{provider}')->group(function () {
        Route::get('/redirect', [OAuthController::class, 'redirectToProvider']); // Redirect to provider
        Route::get('/callback', [OAuthController::class, 'handleProviderCallback']); // Handle provider callback
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/follow/{followed}', [FollowController::class, 'follow']);
    Route::delete('/unfollow/{followed}', [FollowController::class, 'unfollow']);
    Route::get('/following', [FollowController::class, 'getFollowing']);
    Route::get('/followingCount', [FollowController::class, 'getFollowingCount']);
    Route::get('/followers', [FollowController::class, 'getFollowers']);
    Route::get('/followersCount', [FollowController::class, 'getFollowersCount']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts/{post}/like', [LikeController::class, 'likePost']);
    Route::delete('/posts/{post}/unlike', [LikeController::class, 'unlikePost']);
    Route::get('/posts/{post}/likes', [LikeController::class, 'getLikes']);
});
