<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        
        if (method_exists($user, 'save')) {
            $user->save();
        } else {
            return response()->json(["error" => "Method save does not exist on User model"], 500);
        }

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

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $user = Auth::user();
    
        // Check if $user is an instance of User
        if (!$user instanceof User) {
            return response()->json(['error' => 'Authenticated user not found or invalid'], 500);
        }
        
        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if it exists
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }
    
            // Store the new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            
            // Update user's profile picture path
            $user->profile_picture = $path;
            $user->save();
        }
    
        return response()->json(['message' => 'Profile picture updated successfully.', 'user' => $user]);
    }
    
}
