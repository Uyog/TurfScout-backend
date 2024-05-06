<?php

namespace App\Http\Controllers;

use App\Models\Turfs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TurfsController extends Controller
{

    public function createTurf(Request $request){

        $request->validate([
         "turf_name"=>"required",
         "location"=>"required",
        "description"=>"required",
        "amenities"=>"required",
        "price_per_hour"=>"required",
        "availability"=>"required",
        "image_path"=>"image|mimes:jpeg,png,jpg|max:2048"
         
        ]);

        $user = Auth::user();
        if (!$user || $user->role !== 'creator') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
 
        if($request->hasFile("image_path")){
            $filename=$request->file("image_path")->store("turfs", "public");
        }else{
            $filename = null;
        }
        $turf = Turfs::create([
         "turf_name" =>$request->turf_name,
         "location" =>$request->location,
         "description" =>$request->description,
         "amenities" =>$request->amenities,
         "price_per_hour" =>$request->price_per_hour,
         "availability" =>$request->availability,
         "image_path" =>$filename,
         "creator_id" => $user->id,
        ]);
 
        return response()->json($turf);
 }
 
 public function readAllTurfs(){
     $turfs = Turfs::all();
     if(!$turfs){
         return response()->json("No Turf Was found");
     } else {
         return response()->json($turfs);
     }
 }
 
 public function readTurf($id){
     try{
         $turf = Turfs::findOrFail($id);
 
         if($turf){
             return response()->json($turf);
         }
         else{
             return response()->json("No Turf Was Found With The ID: ", $id);
         }
     }
     catch(\Exception $e){
         return response()->json([
             'error' => 'Turf Does Not Exist With Such An ID'
         ],400);
     }
 }
 
 public function updateTurf($id, Request $request){

         $request->validate([
             "turf_name"=>"required",
             "location" =>"required",
             "description" =>"required",
             "amenities" =>"required",
             "price_per_hour" =>"required",
             "availability" =>"required",
             "image_path"=>"image|mimes:jpeg,png,jpg|max:2048"
         ]);

         $user = Auth::user();
         if (!$user || $user->role !== 'creator') {
             return response()->json(['error' => 'Unauthorized'], 401);
         }

         if($request->hasFile("image_path")){
            $filename=$request->file("image_path")->store("turfs", "public"); 
        }else{
            $filename = null;
        }
       

         $turf = Turfs::findOrFail($id);
 
         if($turf){
             $turf->turf_name = $request->turf_name;
             $turf->location = $request->location;
             $turf->description = $request->description;
             $turf->amenities = $request->amenities;
             $turf->price_per_hour = $request->price_per_hour;
             $turf->availability = $request->availability;
             $turf->image_path = $filename;
             $turf->save();
 
             return response()->json($turf);
         }
         else{
             return response()->json("No Turf Was Found With The ID: ", $id);
         }
 }
 
 public function deleteTurf($id){
     try{
         $turf = Turfs::findOrFail($id);
 
         if($turf){
             Turfs::destroy($id);
             return response()->json("Record Has Been Successfully Deleted");
         } else{
             return response()->json("Record Does Not Exist With The ID:", $id);
         }
     }
     catch(\Exception $e){
         return response()->json([
             'error' => 'Record Not Deleted!'
         ],400);
 }
 }

 public function search(Request $request)
{
    $search = $request->input('search');
    $turfs = Turfs::where('turf_name', 'LIKE', "%{$search}%")
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
