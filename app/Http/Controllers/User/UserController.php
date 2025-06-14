<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUser(Request $request)
    {
        $user = $this->userService->getUser($request->user());

        return response()->success(200, 'User retrieved successfully.', $user);
    }

    public function getUserByIdUser(User $user)
    {
        return response()->success(200, 'User retrieved successfully.', $user);
    }

    public function updateUser(UpdateUserRequest $updateRequest)
    {
        $request = $updateRequest->validated();

        $result = $this->userService->updateUser($request, Auth::user());

        return response()->success(200, 'User updated successfully.', $result);
    }
}
