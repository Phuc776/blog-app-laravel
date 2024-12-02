<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OAuthController;
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
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});