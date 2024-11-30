<?php

namespace App\Http\Controllers;

use App\Services\LikeService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    protected $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function likePost($postId)
    {
        $userId = auth()->id();

        $this->likeService->likePost($userId, $postId);

        return response()->json(['message' => 'Liked successfully'], 200);
    }

    public function unlikePost($postId)
    {
        $userId = auth()->id();

        $result = $this->likeService->unlikePost($userId, $postId);

        if ($result) {
            return response()->json(['message' => 'Unliked successfully'], 200);
        }

        return response()->json(['message' => 'Like not found'], 404);
    }

    public function getLikes($postId)
    {
        $likes = $this->likeService->getLikes($postId);

        return response()->json(['likes' => $likes], 200);
    }
}
