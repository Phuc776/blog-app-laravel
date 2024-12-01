<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function getUser(Request $request) {
        $user = $this->userService->getUser($request->user());

        return response()->success(200, 'User retrieved successfully.', $user);
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $validated = $request->validated();

        $user = $this->userService->updateUser($request->user(), $validated);

        return response()->success(200, 'User updated successfully.', $user);
    }

}
