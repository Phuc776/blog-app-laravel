<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(array $data)
    {
        $existingUser = $this->user->where('email', $data['email'])->first();

        if ($existingUser) {
            if ($existingUser->auth_type === 'oauth') {
                // Update auth_type to mixed if the user wants both ways
                $existingUser->update(['auth_type' => 'mixed']);
                return $existingUser;
            }

            throw ValidationException::withMessages([
                'message' => ['This email is already registered. Please log in instead.'],
            ]);
        }

        // Create a new user with local authentication
        $data['auth_type'] = 'local'; // Default to local for traditional registration
        $user = $this->user->create($data);

        event(new Registered($user));
        return $user;
    }


    public function login(array $data)
    {
        $user = $this->user->where('email', $data['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'message' => ['Email or Password is incorrect.'],
            ]);
        }

        // Check if the user can log in with a password
        if (!$user->canLoginWithPassword() || !password_verify($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['Email or Password is incorrect.'],
            ]);
        }

        // Check if the email is verified
        if (!$user->email_verified_at) {
            return ['error' => 'Please verify your email before logging in.'];
        }

        return ['access_token' => $user->access_token, 'token_type' => 'Bearer'];
    }


    public function forgotPassword(string $email)
    {
        Password::sendResetLink(['email' => $email]);
    }

    public function resetPassword(array $data)
    {
        $user = $this->user->where('email', $data['email'])->firstOrFail();
        $user->update(['password' => $data['password']]);
    }

    public function verifyEmail($user, string $hash)
    {
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return false;
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return true;
    }

    public function resendVerification($user)
    {
        event(new Registered($user));
    }

    public function logout($token)
    {
        $token->delete();
    }
}