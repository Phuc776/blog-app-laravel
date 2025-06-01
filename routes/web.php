<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

// Trang chủ (hoặc trang đăng nhập, nếu bạn muốn)
Route::get('/', function () {
    return view('welcome');
});



// // Route to display reset password form with token
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');
