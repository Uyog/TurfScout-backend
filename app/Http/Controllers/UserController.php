<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function currentUser()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function updateName(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(["error" => "User not found with ID: " . $id], 404);
        }

        $user->name = $request->input('name');
        $user->save();

        return response()->json($user);
    }

    public function deleteAccount()
    {
        $userId = Auth::id(); 
        $user = User::find($userId); 
    
        if ($user) {
            $user->delete();
            return response()->json(null, 204);
        } else {
            return response()->json(["error" => "User not found"], 404);
        }
    } 
        
}
