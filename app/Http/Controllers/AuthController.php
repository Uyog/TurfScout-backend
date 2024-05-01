<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name'=>'required',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed',

        ]);

        $user = User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password']),
            
            
        ]);

        $token = $user->createToken('myAppToken')->plainTextToken;


        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request){
       
        $fields = $request->validate([
            'email'=>'required|string',
            'password'=>'required|string',
        ]);
        
        $user = User::where('email', $fields['email'])->first();

        if(!$user||!Hash::check($fields['password'], $user->password)){
            return response([
                'message'=>'Bad Credentials!'
            ], 401);
        }
        $token = $user->createToken('myAppToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' =>$token,
        ];

        return response($response, 201);
    }

  public function logout(Request $request){
    if ($request->user()){
        $request->user()->tokens()->delete();
        return response([
            'message' => 'Logged Out Successfully!',
        ],200);
    }else{
        return response([
            'message' => 'Unauthenticated!',
        ], 401);
    }
  }

  public function sendResetPasswordEmail(Request $request)
  {
      $user = User::where('email', $request->email)->first();

      if (!$user){
          return response()->json(['message' => 'User not found'],404);
      }
      $pin = mt_rand(100000, 999999);
      $user->update(['reset_pin' => $pin]);

      Mail::to($user->email)->send(new ResetPassword($pin));

      return response()->json(['message' => 'Reset password email sent']);
  }



 
  
}
