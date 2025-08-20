<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $data = $r->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password'])
        ]);
        $token = JWTAuth::fromUser($user);
        return view('auth.login')->with('success', 'Account created! Please log in.');


    }

    public function login(Request $r)
    {
        $credentials = $r->only('email','password');
        if (!$token = auth('api')->attempt($credentials)) {
            return back()->withErrors(['email'=>'Invalid credentials']);
        }
        return redirect()->route('home')->with('token', $token);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message'=>'Logged out']);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }
}
