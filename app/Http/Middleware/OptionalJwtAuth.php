<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class OptionalJwtAuth
{
    /**
     * Handle an incoming request.
     *
     * This middleware attempts to authenticate the user via a JWT from a
     * cookie or Authorization header. If successful, it sets the user
     * for the request. If it fails (no token, invalid token), it does
     * nothing and allows the request to proceed as a guest.
     *
     * This is crucial for public pages that need to show a different
     * state for logged-in users (e.g., showing "Sign Out" vs "Sign In").
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // If there's no Bearer header, try the cookie named 'token'
            if (! $request->bearerToken() && $request->hasCookie('token')) {
                $request->headers->set('Authorization', 'Bearer ' . $request->cookie('token'));
            }

            // authenticate() returns the User model or throws an exception
            if ($user = JWTAuth::parseToken()->authenticate()) {
                // Tell Laravel about the authenticated user for this request
                Auth::setUser($user);
            }
        } catch (Exception $e) {
            // An exception means the token is missing, invalid, or expired.
            // We do nothing and let the request continue as a guest.
        }

        return $next($request);
    }
}