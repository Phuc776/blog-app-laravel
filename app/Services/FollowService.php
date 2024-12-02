<?php

namespace App\Services;

use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class FollowService
{
    protected $model;

    public function __construct(Follow $follow)
    {
        $this->model = $follow;
    }

    public function follow($id)
    {
        $exists = $this->model->where('following_id', Auth::id())
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
        $follow = $this->model->where('following_id', Auth::id())
            ->where('followed_id', $followedId);

        if (!$follow) {
            return ['message' => 'Follow not found', 'status' => false, 'data' => $follow];
        }

        $follow->delete();

        return ['message' => 'Unfollowed successfully!', 'status' => true, 'data' => $follow];
    }

    public function getFollowing()
    {
        return $this->model
            ->join('users', 'follows.followed_id', '=', 'users.id')
            ->where('follows.following_id', Auth::id())
            ->select('follows.followed_id', 'users.*')
            ->orderBy('follows.followed_id', 'desc');
    }

    public function getFollowers()
    {
        return $this->model
            ->join('users as u', 'follows.following_id', '=', 'u.id')
            ->where('followed_id', Auth::id())
            ->select('follows.following_id', 'u.*')
            ->orderBy('follows.following_id', 'desc');
    }


    public function getFollowingCount()
    {
        return $this->model->where('following_id', Auth::id())->count();
    }

    public function getFollowersCount()
    {
        return $this->model->where('followed_id', Auth::id())->count();
    }
}
