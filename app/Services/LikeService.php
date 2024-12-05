<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\Post;

class LikeService
{
    protected $model;

    public function __construct(Like $like)
    {
        $this->model = $like;
    }

    public function likePost($postId)
    {
        $post = Post::findOrFail($postId);

        $like = $this->model->firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return ['message' => 'Liked successfully!', 'status' => true, 'data' => $like];
    }

    public function unlikePost($postId)
    {
        $post = Post::findOrFail($postId);

        $like = $this->model->where('user_id', Auth::id())->where('post_id', $post->id);

        if ($like) {
            $like->delete();
            return ['message' => 'Unlike successfully', 'status' => true, 'data' => $like];
        }

        return ['message' => 'Unlike not successfully!', 'status' => false, 'data' => $like];
    }

    public function getLikes($postId)
    {
        $post = Post::findOrFail($postId);

        return $this->model->where('post_id', $post->id)->count();
    }

    public function getIsLiked($postId)
    {
        return $this->model
            ->where('post_id', $postId)
            ->where('user_id', Auth::id())
            ->exists();
    }
}
