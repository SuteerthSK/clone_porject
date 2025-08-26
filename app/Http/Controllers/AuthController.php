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
       
        return response()->json($user, 201);
    }

   public function login(Request $r)
{
    $credentials = $r->only('email','password');

    if (!$token = auth('api')->attempt($credentials)) {
        return response()->json(['email'=>'Invalid credentials'], 401);
    }
    $user = auth('api')->user();

    return response()->json(['token' => $token, 'user' => $user]);
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
