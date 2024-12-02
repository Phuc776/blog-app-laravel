<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\LikeService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    protected $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function likePost(Post $post)
    {

        $result = $this->likeService->likePost($post->id);

        if ($result) {
            return response()->success(201, $result['message'], $result['data']);
        }

        return response()->error(400, $result['message'], null);
    }

    public function unlikePost(Post $post)
    {
        $result = $this->likeService->unlikePost($post->id);

        if ($result) {
            return response()->success(201, $result['message'], $result['data']);
        }

        return response()->error(400, $result['message'], null);
    }

    public function getLikes($postId)
    {
        $likes = $this->likeService->getLikes($postId);

        return response()->success(200, 'Get like count  successfully.', ['count' => $likes]);
    }
}
