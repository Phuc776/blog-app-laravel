<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Post;

class LikeService
{
    public function likePost($userId, $postId)
    {
        $post = Post::findOrFail($postId);

        $like = Like::firstOrCreate([
            'user_id' => $userId,
            'post_id' => $post->id,
        ]);

        return $like;
    }

    public function unlikePost($userId, $postId)
    {
        $post = Post::findOrFail($postId);

        $like = Like::where('user_id', $userId)->where('post_id', $post->id);

        if ($like) {
            $like->delete();
            return true;
        }

        return false;
    }

    public function getLikes($postId)
    {
        $post = Post::findOrFail($postId);

        return Like::where('post_id', $post->id)->with('user')->get();
    }
}
