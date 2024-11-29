<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    // **Register**
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send verification email
        event(new Registered($user));
        return response()->json(['message' => 'User registered successfully. Please check your email to verify your account.'], 201);
    }

    // **Login**
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->email_verified_at) {
            return response()->json(['message' => 'Please verify your email before logging in.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    // **Forgot Password**
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Password::sendResetLink($request->only('email'));

        return response()->json(['message' => 'Password reset link sent to your email.']);
    }

    // **Reset Password**
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $user = User::where('email', $validated['email'])->firstOrFail();
    
        // Verify the token (implementation depends on your reset flow)
    
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
    
        return response()->json(['message' => 'Password reset successful.']);
    }

    // **Verify Email**
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        return response()->json(['message' => 'Email verified successfully.']);
    }

    // **Resend Verification**
    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        event(new Registered($user));

        return response()->json(['message' => 'Verification email resent.']);
    }

    // **Logout**
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    // **Create Sample User** - thêm đoạn mã tạo người dùng mẫu vào đây
    public function createSampleUser(Request $request)
    {
        // Tạo tài khoản với mã hóa mật khẩu
        User::create([
            'name' => 'Ngô Văn Sinh',
            'email' => 'ngovansinhqni@gmail.com',
            'password' => Hash::make('Sinh12345678'), // Mã hóa mật khẩu
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => null, // Bỏ qua xác minh email
        ]);

        // Trả về phản hồi sau khi tạo người dùng thành công
        return response()->json(['message' => 'Sample user created successfully']);
    }
}
