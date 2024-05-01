<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function createRefund(Request $request){
        $request->validate([
         "user_id"=>"required",
         "booking_id"=>"required",
        "refund_amount"=>"required",
        ]);
 
        $refund = Refund::create([
         "user_id" =>$request->user_id,
         "booking_id" =>$request->booking_id,
         "refund_amount" =>$request->refund_amount,
        ]);
 
        return response()->json($refund);
 }
 
 public function readAllRefunds(){
     //$refunds = Refund::all();
     $refunds = Refund::join('bookings','refunds.booking_id', '=', 'bookings.id')
                        ->join('users', 'refunds.user_id', '=', 'users.id') 
                        ->select('refunds.*','bookings.booking_status as booking_status', 'users.name as user_name', 'users.email as user_email' )->get();

     if(!$refunds){
         return response()->json("No Refund Was found");
     } else {
         return response()->json($refunds);
     }
 }
 
 public function readRefund($id){
     try{
         $refund = Refund::findOrFail($id);
 
         if($refund){
             return response()->json($refund);
         }
         else{
             return response()->json("No Refund Was Found With The ID: ", $id);
         }
     }
     catch(\Exception $e){
         return response()->json([
             'error' => 'Refund Does Not Exist With Such An ID'
         ],400);
     }
 }
 
 public function updateRefund($id, Request $request){
     try{
         $request->validate([
             "user_id"=>"required",
             "booking_id"=>"required",
             "refund_amount"=>"required",
         ]);

        
         $refund = Refund::findOrFail($id);
 
         if($refund){
             $refund->user_id = $request->user_id;
             $refund->booking_id = $request->booking_id;
             $refund->refund_amount = $request->refund_amount;
             $refund->save();
 
             return response()->json($refund);
         }
         else{
             return response()->json("No Refund Was Found With The ID: ", $id);
         }
 
     }
     catch(\Exception $e){
         return response()->json([
             'error' => 'Unable to Update Record!'
         ],400);
     }
 }
 
 public function deleteRefund($id){
     try{
         $refund = Refund::findOrFail($id);
 
         if($refund){
             Refund::destroy($id);
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
