<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        // Bảo vệ tất cả các route bằng middleware 'auth:sanctum'
        $this->middleware('auth:sanctum');
    }

    // Lấy tất cả bài viết và phân trang
    public function index()
{
    $posts = Post::paginate(2); // Phân trang với 2 bài viết mỗi trang

    return response()->json([
        'status' => 'success',
        'message' => 'List of articles',
        'data' => [
            'posts' => $posts->items(), // Chỉ trả về danh sách bài viết
            'pagination' => [ // Thêm thông tin phân trang
                'total' => $posts->total(),
                'per_page' => $posts->perPage(),
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'next_page_url' => $posts->nextPageUrl(),
                'prev_page_url' => $posts->previousPageUrl(),
            ]
        ],
    ], 200);
}

    // Tạo bài viết mới
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|boolean',
        ]);

        // Tạo bài viết
        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Posts already created',
            'data' => $post,
        ], 201);
    }

    // Lấy bài viết cụ thể
    public function show($id)
    {
        $post = Post::findOrFail($id); // Tìm bài viết theo ID
        return response()->json([
            'status' => 'success',
            'message' => 'Article details',
            'data' => $post,
        ], 200);
    }

    // Cập nhật bài viết
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Kiểm tra quyền sở hữu
        if ($post->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You dont have permission to update this article',
                'data' => null,
            ], 403);
        }

        // Validate dữ liệu
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|boolean',
        ]);

        // Cập nhật bài viết
        $post->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Article has been updated',
            'data' => $post,
        ], 200);
    }

    // Xóa bài viết
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Kiểm tra quyền sở hữu
        if ($post->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You dont have the right to delete this article',
                'data' => null,
            ], 403);
        }

        // Xóa bài viết
        $post->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted article',
            'data' => null,
        ], 200);
    }
}
