<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\Follow\FollowRequest;
use App\Http\Resources\FollowResource;

use App\Models\User;
use App\Services\FollowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    protected $followService;

    public function __construct(FollowService $followService)
    {
        $this->followService = $followService;
    }

    public function follow(User $followed)
    {
        $result = $this->followService->follow($followed->id);

        if ($result) {
            return response()->success(201, $result['message'], $result['data']);
        }

        return response()->error(400, $result['message'], null);
    }

    public function unfollow(User $followed)
    {
        $result = $this->followService->unfollow($followed->id);

        if ($result) {
            return response()->success(200, $result['message'], $result['data']);
        }

        return response()->error(404, $result['message'], null);
    }

    public function getFollowing(Request $request)
    {
        $result = $this->followService->getFollowing();
        return FollowResource::apiPaginate($result, $request);
    }

    public function getFollowers(Request $request)
    {
        $result = $this->followService->getFollowers();
        return FollowResource::apiPaginate($result, $request);
    }

    public function getFollowingCount()
    {
        $count = $this->followService->getFollowingCount();

        return response()->success(200, 'Get following count successfully.', ['count' => $count]);
    }

    public function getFollowersCount()
    {
        $count = $this->followService->getFollowersCount();

        return response()->success(200, 'Get followers count  successfully.', ['count' => $count]);
    }
}
