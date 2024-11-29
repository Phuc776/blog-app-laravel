<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

// Trang chủ (hoặc trang đăng nhập, nếu bạn muốn)
Route::get('/', function () {
    return view('welcome');
});

// ** Đăng ký và Đăng nhập **
Route::get('register', function () {
    return view('posts.register');  // Trang đăng ký
})->name('register');
Route::get('login', function () {
    return view('posts.login');  // Trang đăng nhập
})->name('login');

// Đăng ký và đăng nhập xử lý
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Đảm bảo chỉ người dùng đã đăng nhập mới có quyền truy cập vào các route này
Route::middleware('auth')->group(function () {
    // Các route liên quan đến quản lý bài viết
    Route::resource('posts', PostController::class);
    
    // Logout
    Route::post('logout', [AuthController::class, 'logout']);
});
// Thêm vào trong routes/web.php hoặc routes/api.php
Route::get('/create-sample-user', [AuthController::class, 'createSampleUser']);


