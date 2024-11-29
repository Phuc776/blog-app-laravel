<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PostController; // Đảm bảo import PostController
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

// Protected routes requiring Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    // Route to get all posts
    Route::get('/posts', [PostController::class, 'index']);  // Get all posts

    // Route to create a post
    Route::post('/posts', [PostController::class, 'store']);  // Create a post

    // Route to get a single post by ID
    Route::get('/posts/{id}', [PostController::class, 'show']);  // Get a single post

    // Route to update a post
    Route::put('/posts/{id}', [PostController::class, 'update']);  // Update a post

    // Route to delete a post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);  // Delete a post
});
