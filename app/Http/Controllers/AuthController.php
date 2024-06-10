<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
           
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => 'user', 
        ]);

        $token = $user->createToken('myAppToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    public function registerFromOtherApp(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $existingUser = User::where('email', $request->email)->where('role', 'user')->first();

        if ($existingUser) {
            return response()->json(['error' => 'You already have a user account. Please login instead.'], 400);
        }


        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => 'creator',
        ]);

        $token = $user->createToken('myAppToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        if ($user->role === 'creator') {
            return response()->json(['error' => 'Creators cannot login to this app. Please create a user account.'], 401);
        }

        $token = $user->createToken('myAppToken')->plainTextToken;


        return response()->json([
            'user' => $user,
            'token_type' => 'Bearer',
            'token' => $token,
            
        ], 201);
    }

    public function loginAsCreator(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        if ($user->role !== 'creator') {
            return response()->json(['error' => 'Only creators can login to this app.'], 401);
        }

        $token = $user->createToken('myAppToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token_type' => 'Bearer',
            'token' => $token,
        ], 201);
    }


    
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }
}
