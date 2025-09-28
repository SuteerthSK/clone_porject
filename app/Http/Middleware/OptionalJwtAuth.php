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
   
    public function handle(Request $request, Closure $next): Response
    {
        try {
            
            if (! $request->bearerToken() && $request->hasCookie('token')) {
                $request->headers->set('Authorization', 'Bearer ' . $request->cookie('token'));
            }

            
            if ($user = JWTAuth::parseToken()->authenticate()) {
                
                Auth::setUser($user);
            }
        } catch (Exception $e) {
            
        }

        return $next($request);
    }
}