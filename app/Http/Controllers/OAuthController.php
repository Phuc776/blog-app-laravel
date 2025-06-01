<?php

namespace App\Http\Controllers;

use App\Services\OAuthService;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    private $oAuthService;

    public function __construct(OAuthService $oAuthService)
    {
        $this->oAuthService = $oAuthService;
    }

    /**
     * Redirect to the OAuth provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function redirectToProvider($provider)
    {
        try {
            $redirectUrl = $this->oAuthService->getRedirectUrl($provider);
            return response()->success(200, 'Redirect URL generated successfully.', ['redirect_url' => $redirectUrl]);
        } catch (\Exception $e) {
            return response()->error(500, 'Failed to generate redirect URL.', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle the provider's callback.
     *
     * @param string $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $result = $this->oAuthService->handleCallback($provider);

            if (isset($result['error'])) {
                return response()->error(401, 'Authentication failed.', $result['error']);
            }

            return response()->success(200, 'User authenticated successfully.', $result);
        } catch (\Exception $e) {
            return response()->error(500, 'Error handling provider callback.', ['error' => $e->getMessage()]);
        }
    }
}