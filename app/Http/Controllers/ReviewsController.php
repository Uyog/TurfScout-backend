<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function createReview(Request $request){
        $request->validate([
         "user_id"=>"required",
         "turf_id"=>"required",
        "ratings"=>"required",
        "comments"=>"required",
        ]);
 
        $review = Reviews::create([
         "user_id" =>$request->user_id,
         "turf_id" =>$request->turf_id,
         "ratings" =>$request->ratings,
         "comments" =>$request->comments,
        ]);
 
        return response()->json($review);
 }
 
 public function readAllReviews(){
     //$reviews = Reviews::all();
     $reviews = Reviews::join('turfs','reviews.turf_id', '=', 'turfs.id')
                        ->join('users', 'reviews.user_id', '=', 'users.id') 
                        ->select('reviews.*','turfs.turf_name as turf_name', 'users.name as user_name', 'users.email as user_email' )->get();
     if(!$reviews){
         return response()->json("No Review Was found");
     } else {
         return response()->json($reviews);
     }
 }
 
 public function readReview($id){
     try{
         $review = Reviews::findOrFail($id);
 
         if($review){
             return response()->json($review);
         }
         else{
             return response()->json("No Review Was Found With The ID: ", $id);
         }
     }
     catch(\Exception $e){
         return response()->json([
             'error' => 'Review Does Not Exist With Such An ID'
         ],400);
     }
 }
 
 public function updateReview($id, Request $request){
     try{
         $request->validate([
            "user_id"=>"required",
            "turf_name"=>"required",
            "ratings"=>"required",
            "comments"=>"required",
         ]);

        
         $review = Reviews::findOrFail($id);
 
         if($review){
             $review->user_id = $request->user_id;
             $review->turf_id = $request->turf_id;
             $review->ratings = $request->ratings;
             $review->comments = $request->comments;
             $review->save();
 
             return response()->json($review);
         }
         else{
             return response()->json("No Review Was Found With The ID: ", $id);
         }
 
     }
     catch(\Exception $e){
         return response()->json([
             'error' => 'Unable to Update Record!'
         ],400);
     }
 }
 
 public function deleteReview($id){
     try{
         $review = Reviews::findOrFail($id);
 
         if($review){
             Reviews::destroy($id);
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
}
