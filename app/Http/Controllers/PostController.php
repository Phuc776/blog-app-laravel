<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        // Yêu cầu người dùng phải đăng nhập với Bearer Token thông qua middleware 'auth:sanctum'
        $this->middleware('auth:sanctum');
    }

    // Hiển thị tất cả các bài viết
    public function index()
    {
        $posts = Post::all();  // Lấy tất cả bài viết
        return response()->json($posts, 200, [], JSON_UNESCAPED_UNICODE);  // Trả về danh sách bài viết dưới dạng JSON
    }

    // Hiển thị một bài viết cụ thể
    public function show($id)
    {
        $post = Post::findOrFail($id);  // Tìm bài viết theo ID
        return response()->json($post, 200, [], JSON_UNESCAPED_UNICODE);  // Trả về bài viết dưới dạng JSON
    }

    // Hiển thị form để tạo bài viết (có thể không cần trong API)
    public function create()
    {
        return response()->json([
            'message' => 'Truy cập API để tạo bài viết.'
        ], 200, [], JSON_UNESCAPED_UNICODE);  // Thông báo cho người dùng biết rằng API này cần được gọi
    }

    // Lưu bài viết vào cơ sở dữ liệu
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|boolean',
        ]);

        // Tạo bài viết mới
        $post = Post::create([
            'user_id' => auth()->id(),  // Gán user_id là ID của người dùng hiện tại
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return response()->json([  // Trả về phản hồi JSON
            'message' => 'Bài viết đã được tạo.',
            'post' => $post
        ], 201, [], JSON_UNESCAPED_UNICODE);  // Trả về mã trạng thái 201 (Created)
    }

    // Hiển thị form để chỉnh sửa bài viết (có thể không cần trong API)
    public function edit($id)
    {
        $post = Post::findOrFail($id);  // Tìm bài viết theo ID
        return response()->json($post, 200, [], JSON_UNESCAPED_UNICODE);  // Trả về bài viết dưới dạng JSON để chỉnh sửa
    }

    // Cập nhật bài viết vào cơ sở dữ liệu
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);  // Tìm bài viết theo ID

        // Kiểm tra quyền của người dùng
        if ($post->user_id !== auth()->id()) {
            return response()->json(['error' => 'Bạn không có quyền cập nhật bài viết này.'], 403, [], JSON_UNESCAPED_UNICODE);  // Trả về lỗi nếu người dùng không phải là chủ sở hữu bài viết
        }

        // Validate dữ liệu
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|boolean',
        ]);

        // Cập nhật bài viết
        $post->update($request->all());

        return response()->json([  // Trả về thông báo thành công
            'message' => 'Bài viết đã được cập nhật.',
            'post' => $post
        ], 200, [], JSON_UNESCAPED_UNICODE);  // Trả về mã trạng thái 200 (OK)
    }

    // Xóa bài viết
    public function destroy($id)
    {
        $post = Post::findOrFail($id);  // Tìm bài viết theo ID

        // Kiểm tra quyền của người dùng
        if ($post->user_id !== auth()->id()) {
            return response()->json(['error' => 'Bạn không có quyền xóa bài viết này.'], 403, [], JSON_UNESCAPED_UNICODE);  // Trả về lỗi nếu người dùng không phải là chủ sở hữu bài viết
        }

        // Xóa bài viết
        $post->delete();

        return response()->json(['message' => 'Bài viết đã được xóa.'], 200, [], JSON_UNESCAPED_UNICODE);  // Trả về thông báo thành công
    }
}
