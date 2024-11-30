<?php

namespace App\Http\Controllers;

use App\Http\Requests\Web\Auth\LoginRequest;
use App\Http\Requests\Web\Auth\ForgotPasswordRequest;
use App\Http\Requests\Web\Auth\ResetPasswordRequest;
use App\Http\Requests\Web\Auth\ResendVerificationRequest;
use App\Http\Requests\Web\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $this->authService->register($request->validated());
        return response()->success(201, 'User registered successfully. Please check your email to verify your account.', null);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        if (isset($result['error'])) {
            return response()->error(403, $result['error']);
        }

        return response()->success(200, 'Login successful.', $result);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->authService->forgotPassword($request->email);
        return response()->success(200, 'Password reset link sent to your email.', null);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $this->authService->resetPassword($request->validated());
        return response()->success(200, 'Password reset successful.', null);
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!$this->authService->verifyEmail($user, $hash)) {
            return response()->error(403, 'Invalid verification link.');
        }

        return response()->success(200, 'Email verified successfully.', null);
    }

    public function resendVerification(ResendVerificationRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->error(404, 'User not found.');
        }

        if ($user->hasVerifiedEmail()) {
            return response()->success(200, 'Email already verified.', null);
        }

        $this->authService->resendVerification($user);

        return response()->success(200, 'Verification email resent.', null);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user()->currentAccessToken());
        return response()->success(200, 'Logged out successfully.', null);
    }
}
