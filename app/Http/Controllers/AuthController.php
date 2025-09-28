<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:user,admin',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('auth.login.view')
            ->with('success', 'Registration successful! Please log in.');
    }

    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
        }

        
        $cookie = cookie(
            'token',
            $token,
            0,          
            '/',        
            null,       
            false,      
            true,       
            false,      
            'Lax'       
        );

        
        $redirect = Auth::user()->role === 'admin'
            ? route('admin.books.index')
            : route('books.index');

        return redirect()->intended($redirect)->withCookie($cookie);
    }

   
    public function logout(Request $request)
    {
        try {
            $token = $request->cookie('token');
            if ($token) {
                JWTAuth::setToken($token)->invalidate();
            }
        } catch (\Exception $e) {
            
        }

        
        $forget = Cookie::forget('token');

        return redirect()->route('auth.login.view')->withCookie($forget);
    }

    
    public function me(Request $request)
    {
        try {
            $token = $request->bearerToken() ?? $request->cookie('token');

            if (! $token) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            JWTAuth::setToken($token);
            $user = JWTAuth::authenticate();

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }
}