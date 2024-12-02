<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    // public function redirectToProvider($provider)
    // {
    //     return Socialite::driver($provider)->stateless()->redirect();
    // }

    // public function handleProviderCallback($provider)
    // {
    //     try {
    //         $socialUser = Socialite::driver($provider)->stateless()->user();

    //         // Check if the user already exists
    //         $user = User::where('email', $socialUser->getEmail())->first();
    
    //         if (!$user) {
    //             // Create a new user if they don't exist
    //             $user = User::create([
    //                 'name' => $socialUser->getName(),
    //                 'email' => $socialUser->getEmail(),
    //                 'auth_type' => $provider,
    //                 'email_verified_at' => now(),  // Consider OAuth-verified
    //             ]);
    //         }
    
    //         // Update or create the user_oauth record
    //         UserOAuth::updateOrCreate(
    //             ['user_id' => $user->id, 'provider_id' => $socialUser->getId()],
    //             ['access_token' => $socialUser->token]
    //         );
    
    //         // Generate a token for the user
    //         $token = $user->createToken('auth_token')->plainTextToken;
    
    //         return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
        
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Authentication failed.'], 500);
    //     }
    // }
}