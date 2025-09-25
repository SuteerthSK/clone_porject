<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Ensure a valid JWT (from Authorization header OR cookie) and
     * set the authenticated user into Laravel's Auth system so Blade
     * @auth / auth()->check() work.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // If there's no Bearer header, try the cookie named 'token'
            if (! $request->bearerToken() && $request->hasCookie('token')) {
                $request->headers->set('Authorization', 'Bearer ' . $request->cookie('token'));
            }

            // authenticate() returns the User model (or throws)
            $user = JWTAuth::parseToken()->authenticate();

            if ($user) {
                // Tell Laravel about the authenticated user (so auth() helpers work)
                Auth::setUser($user);

                // Also set a request user resolver (for request->user() & other uses)
                $request->setUserResolver(function () use ($user) {
                    return $user;
                });
            }
        } catch (Exception $e) {
            // Token missing/invalid/expired -> redirect to login
            return redirect()->route('auth.login.view')
                ->with('error', 'Session expired, please login again.');
        }

        return $next($request);
    }
}
