<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    // Tạo bài viết mới
    public function store($data)
    {
        return Post::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => $data['status'],
        ]);
    }

    // Cập nhật bài viết
    public function update($post, $data)
    {
        return $post->update($data);
    }

    // Xóa bài viết
    public function destroy($post)
    {
        return $post->delete();
    }

    // Lấy tất cả bài viết với phân trang
    public function getAllPosts($perPage = 2)
    {
        return Post::paginate($perPage);
    }

    // Lấy bài viết cụ thể
    public function getPostById($id)
    {
        return Post::findOrFail($id);
    }
}
