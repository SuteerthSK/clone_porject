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
    
    public function handle(Request $request, Closure $next): Response
    {
        try {
            
            if (! $request->bearerToken() && $request->hasCookie('token')) {
                $request->headers->set('Authorization', 'Bearer ' . $request->cookie('token'));
            }

            
            $user = JWTAuth::parseToken()->authenticate();

            if ($user) {
                
                Auth::setUser($user);

                
                $request->setUserResolver(function () use ($user) {
                    return $user;
                });
            }
        } catch (Exception $e) {
           
            return redirect()->route('auth.login.view')
                ->with('error', 'Session expired, please login again.');
        }

        return $next($request);
    }
}
