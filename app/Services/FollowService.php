<?php

namespace App\Services;

use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class FollowService
{
    public function follow($id)
    {
        $exists = Follow::where('following_id', Auth::id())
            ->where('followed_id', $id)
            ->exists();

        if ($exists) {
            return ['message' => 'Already following this user', 'status' => false];
        }

        $follow = Follow::create([
            'following_id' => Auth::id(),
            'followed_id' => $id,
            'created_at' => now(),
        ]);

        return ['message' => 'Followed successfully!', 'status' => true, 'data' => $follow];
    }

    public function unfollow($followedId)
    {
        $follow = Follow::where('following_id', Auth::id())
            ->where('followed_id', $followedId);

        if (!$follow) {
            return ['message' => 'Follow not found', 'status' => false];
        }

        $follow->delete();

        return ['message' => 'Unfollowed successfully', 'status' => true];
    }


    public function getFollowing()
    {
        $follow = Follow::where('following_id', Auth::id())
            ->with('followed')
            ->get();

        return $follow;
    }

    public function getFollowers()
    {
        $follow = Follow::where('followed_id', Auth::id())
            ->with('follower')
            ->get();

        return $follow;
    }
}
