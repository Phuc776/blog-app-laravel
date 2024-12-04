<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class AuthTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $authType)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->error(401, 'Unauthorized', ['message' => 'You must be logged in to access this resource.']);
        }

        if ($user->auth_type !== $authType && $user->auth_type !== 'mixed') {
            return response()->error(403, 'Access Denied', [ 'message'=> 'You do not have permission to access this resource.']);
        }

        return $next($request);
    }
}
