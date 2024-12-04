<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\Post\IndexPostRequest;
use App\Http\Requests\Api\Post\PostRequest;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Http\Requests\Api\Post\ShowPostRequest;
use App\Http\Requests\Api\Post\DestroyPostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Lấy tất cả bài viết và phân trang
    public function index(IndexPostRequest $request)
    {
        $validated = $request->validated();

        $query = Post::query();

        // Phân trang
        $perPage = $validated['per_page'] ?? 2;
        $page = $validated['page'] ?? 1;

        // Sắp xếp
        $sort = $validated['sort'] ?? 'created_at';
        $order = $validated['order'] ?? 'desc';

        $posts = $query->orderBy($sort, $order)->paginate($perPage);

        return response()->success(200, 'List of articles', [
            'posts' => $posts->items(),
            'pagination' => [
                'total' => $posts->total(),
                'per_page' => $posts->perPage(),
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'next_page_url' => $posts->nextPageUrl(),
                'prev_page_url' => $posts->previousPageUrl(),
            ]
        ]);
    }

    // Tạo bài viết mới
    public function store(PostRequest $request)
    {
        $validated = $request->validated();

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
        ]);

        return response()->success(201, 'Post created successfully.', $post);
    }

    // Cập nhật bài viết
    public function update(UpdatePostRequest $request, $id)
    {
        $validated = $request->validated();
        $post = Post::findOrFail($id);

        // Kiểm tra quyền sở hữu
        if ($post->user_id !== auth()->id()) {
            return response()->error(403, 'You don\'t have permission to update this article', null);
        }

        $post->update($validated);

        return response()->success(200, 'Article updated successfully.', $post);
    }

    // Xem chi tiết bài viết
    public function show(ShowPostRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        return response()->success(200, 'Article details', $post);
    }

    // Xóa bài viết
    public function destroy(DestroyPostRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        // Kiểm tra quyền sở hữu
        if ($post->user_id !== auth()->id()) {
            return response()->error(403, 'You don\'t have permission to delete this article', null);
        }

        $post->delete();

        return response()->success(200, 'Article deleted successfully.', null);
    }

    public function getPostByUserId($userId)
    {
        // Lấy tất cả các bài viết của user với id là $userId
        $posts = Post::where('user_id', $userId)->get();

        if ($posts->isEmpty()) {
            return response()->error(404, 'No posts found for this user.', null);
        }

        return response()->success(200, 'Posts by user retrieved successfully.', $posts);
    }

    public function countPostsByUserId($userId)
    {
        // Đếm số bài viết của user với id là $userId
        $count = Post::where('user_id', $userId)->count();

        return response()->success(200, 'Post count retrieved successfully.', ['count' => $count]);
    }
}
