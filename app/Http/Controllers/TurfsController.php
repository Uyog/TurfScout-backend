<?php

namespace App\Http\Controllers;

use App\Models\Turfs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TurfsController extends Controller
{

    public function createTurf(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'price' => 'required|numeric',
        ]);

        $user = Auth::user();
        if (!$user || $user->role !== 'creator') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store("turfs", "public");
            $imageUrl = Storage::url($imagePath);
        }

        $turf = Turfs::create([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'image_url' => $imageUrl,
            'price' => $request->price,
            'creator_id' => $user->id,
        ]);

        return response()->json($turf, 201);
    }
 
    public function readAllTurfs()
    {
        $turfs = Turfs::all();
        return $turfs->isEmpty()
            ? response()->json("No Turf Was found", 404)
            : response()->json($turfs);
    }
 
    public function readTurf($id)
    {
        try {
            $turf = Turfs::findOrFail($id);
            return response()->json($turf);
        } catch (\Exception $e) {
            return response()->json(["error" => "No Turf Was Found With The ID: {$id}"], 404);
        }
    }
 
    public function updateTurf($id, Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "location" => "required|string|max:255",
            "description" => "required|string",
            "image" => "image|mimes:jpeg,png,jpg|max:2048",
            "price" => "required|numeric",
        ]);

        $user = Auth::user();
        if (!$user || $user->role !== 'creator') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $turf = Turfs::findOrFail($id);

            $imageUrl = $request->hasFile("image") ? Storage::url($request->file("image")->store("turfs", "public")) : $turf->image_url;

            $turf->update([
                'name' => $request->name,
                'location' => $request->location,
                'description' => $request->description,
                'image_url' => $imageUrl,
                'price' => $request->price,
            ]);

            return response()->json($turf);
        } catch (\Exception $e) {
            return response()->json(["error" => "No Turf Was Found With The ID: {$id}"], 404);
        }
    }
 
    public function deleteTurf($id)
    {
        try {
            $turf = Turfs::findOrFail($id);
            $turf->delete();
            return response()->json("Record Has Been Successfully Deleted");
        } catch (\Exception $e) {
            return response()->json(["error" => "Record Does Not Exist With The ID: {$id}"], 404);
        }
    }

 public function search(Request $request)
{
    $search = $request->input('search');
    $turfs = Turfs::where('name', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->get();
    return response()->json($turfs);
    
}

public function getTurfImageUrl($filename)
{
    $url = Storage::url("turfs/{$filename}");
    return $url;
    
}
}
