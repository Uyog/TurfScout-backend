<?php

namespace App\Http\Controllers;

use App\Models\Turfs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 

class CreatorController extends Controller
{
    public function becomeCreator(Request $request)
    {
        // Fetch the authenticated user by ID
        $user = User::find(Auth::id());

        if ($user) {
            // Update user's role to 'creator' (assuming the 'role' field exists in the users table)
            $user->role = 'creator';
            $user->save();

            // Additional logic to store any other information related to creators

            return response()->json(['message' => 'Successfully became a creator'], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function createTurf(Request $request)
    {
        // Validate turf creation data
        $request->validate([
            'turf_name' => 'required',
            'location' => 'required',
            'description' => 'required',
            'amenities' => 'required',
            'price_per_hour' => 'required',
            'availability' => 'required',
            'image_path' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is a creator
        if ($user && $user->role === 'creator') {
            // Handle image upload
            if ($request->hasFile('image_path')) {
                $filename = $request->file('image_path')->store('turfs', 'public');
            } else {
                $filename = null;
            }

            // Create a new turf
            $turf = Turfs::create([
                'turf_name' => $request->turf_name,
                'location' => $request->location,
                'description' => $request->description,
                'amenities' => $request->amenities,
                'price_per_hour' => $request->price_per_hour,
                'availability' => $request->availability,
                'image_path' => $filename,
                'creator_id' => $user->id, // Assign the creator ID
            ]);

            return response()->json($turf);
        } else {
            return response()->json(['error' => 'Only creators can create turfs'], 403);
        }
    }



    public function updateTurf(Request $request, $id)
    {
        // Find the turf by its ID
        $turf = Turfs::find($id);

        // If turf exists
        if ($turf) {
            // Check if the authenticated user is the creator of the turf
            if ($turf->creator_id === Auth::id()) {
                // Validate turf update data
                $request->validate([
                    'turf_name' => 'required',
                    'location' => 'required',
                    'description' => 'required',
                    'amenities' => 'required',
                    'price_per_hour' => 'required',
                    'availability' => 'required',
                    'image_path' => 'image|mimes:jpeg,png,jpg|max:2048',
                ]);

                // Handle image upload
                if ($request->hasFile('image_path')) {
                    $filename = $request->file('image_path')->store('turfs', 'public');
                    $turf->image_path = $filename;
                }

                // Update turf data
                $turf->update([
                    'turf_name' => $request->turf_name,
                    'location' => $request->location,
                    'description' => $request->description,
                    'amenities' => $request->amenities,
                    'price_per_hour' => $request->price_per_hour,
                    'availability' => $request->availability,
                ]);

                return response()->json(['message' => 'Turf updated successfully'], 200);
            } else {
                return response()->json(['error' => 'You are not authorized to update this turf'], 403);
            }
        } else {
            return response()->json(['error' => 'Turf not found'], 404);
        }
    }

    // Method to delete a turf
    public function deleteTurf($id)
    {
        // Find the turf by its ID
        $turf = Turfs::find($id);

        // If turf exists
        if ($turf) {
            // Check if the authenticated user is the creator of the turf
            if ($turf->creator_id === Auth::id()) {
                // Delete the turf
                $turf->delete();
                return response()->json(['message' => 'Turf deleted successfully'], 200);
            } else {
                return response()->json(['error' => 'You are not authorized to delete this turf'], 403);
            }
        } else {
            return response()->json(['error' => 'Turf not found'], 404);
        }
    }

}
