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
    /**
     * Register new user
     */
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

    /**
     * Login with JWT and store token in a session-only HttpOnly cookie
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
        }

        // ✅ Create a session cookie by setting the lifetime to 0 minutes.
        // This cookie will be automatically deleted when the browser is closed.
        $cookie = cookie(
            'token',
            $token,
            0,          // ✅ *** THIS IS THE CHANGE ***
            '/',        // path
            null,       // domain
            false,      // secure (set true in production with HTTPS)
            true,       // httpOnly
            false,      // raw
            'Lax'       // sameSite
        );

        // Redirect based on role and attach cookie
        $redirect = Auth::user()->role === 'admin'
            ? route('admin.books.index')
            : route('books.index');

        return redirect()->intended($redirect)->withCookie($cookie);
    }

    /**
     * Logout (invalidate token and remove cookie)
     */
    public function logout(Request $request)
    {
        try {
            $token = $request->cookie('token');
            if ($token) {
                JWTAuth::setToken($token)->invalidate();
            }
        } catch (\Exception $e) {
            // ignore errors from invalidation
        }

        // Forget cookie with Laravel helper
        $forget = Cookie::forget('token');

        return redirect()->route('auth.login.view')->withCookie($forget);
    }

    /**
     * Return authenticated user (supports header or cookie)
     */
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