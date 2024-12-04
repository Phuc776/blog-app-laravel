<?php

namespace App\Services;

use App\Models\User;
use Log;
use Auth;
use Hash;
use Laravel\Socialite\Facades\Socialite;

class OAuthService
{
    private $user;

    public function __construct(User $userModel)
    {
        $this->user = $userModel;
    }

    /**
     * Get the redirect URL for a provider.
     *
     * @param string $provider
     * @return string
     * @throws \Exception
     */
    public function getRedirectUrl($provider)
    {
        if (!in_array($provider, ['google', 'facebook', 'github'])) {
            throw new \Exception("Unsupported provider: $provider");
        }
        return Socialite::driver($provider)->redirect()->getTargetUrl();
    }


    public function handleCallback($provider)
    {
        $OAuthUser = Socialite::driver($provider)->stateless()->user();

        // Check for an existing user
        $user = $this->user->where('email', $OAuthUser->email)->first();

        if (!$user) {
            // Create a new user for OAuth-only authentication
            $user = $this->user->create([
                "name" => $OAuthUser->name,
                "email" => $OAuthUser->email,
                "auth_type" => 'oauth',
                "provider_name" => $provider,
                "provider_id" => $OAuthUser->id,
                "password" => null,
            ]);
        } else {
            // If the user exists but is local, switch to mixed
            if ($user->auth_type === 'local') {
                $user->update([
                    "auth_type" => 'mixed',
                    "provider_name" => $provider,
                    "provider_id" => $OAuthUser->id,
                ]);
            }
        }

        // Log in the user
        Auth::login($user);

        $token = $user->createToken("user")->plainTextToken;

        return [
            "name" => $user->name,
            "email" => $user->email,
            "auth_type" => $user->auth_type,
            "token" => $token,
        ];
    }

}
