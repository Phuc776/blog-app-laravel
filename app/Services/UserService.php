<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserService {
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function getUser(User $user)
    {
        return $user;
    }

    public function updateUser(User $user, array $data)
    {
        $user->name = $data['name'];
        $user->avatar = $data['avatar'] ?? $user->avatar; // Update avatar only if provided
        $user->save();

        return $user->only(['name','avatar']);
    }

}