<?php

namespace App\Http\Controllers;

use App\Services\FollowService;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    protected $followService;

    public function __construct(FollowService $followService)
    {
        $this->followService = $followService;
    }

    public function follow($followedId)
    {
        if ($followedId == Auth::id()) {
            return response()->json(['message' => 'You cannot follow yourself.'], 400);
        }

        $result = $this->followService->follow($followedId);

        return response()->json(['message' => $result['message']], $result['status'] ? 201 : 400);
    }

    public function unfollow($followedId)
    {
        $result = $this->followService->unfollow($followedId);

        return response()->json(['message' => $result['message']], $result['status'] ? 200 : 404);
    }


    public function getFollowing()
    {
        $follows = $this->followService->getFollowing();

        return response()->json(['data' => $follows]);
    }

    public function getFollowers()
    {
        $followers = $this->followService->getFollowers();

        return response()->json(['data' => $followers]);
    }
}
