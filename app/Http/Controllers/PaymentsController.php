<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function createPayment(Request $request){
        $request->validate([
        "user_id"=>"required",
        "booking_id"=>"required",
        "amount"=>"required",
        "payment_status"=>"required",
        "payment_method"=>"required",
         
        ]);
 
        $payment = Payments::create([
         "user_id" =>$request->user_id,
         "booking_id" =>$request->booking_id,
         "amount" =>$request->amount,
         "payment_status" =>$request->payment_status,
         "payment_method" =>$request->payment_method,
        ]);
 
        return response()->json($payment);
 }
 
 public function readAllPayments(){
     //$payments = Payments::all();
     $payments = Payments::join('bookings','payments.booking_id', '=', 'bookings.id')
                           ->join('users', 'payments.user_id', '=', 'users.id') 
                            ->select('payments.*','bookings.total_price as total_price', 'users.name as user_name', 'users.email as user_email' )->get();
     if(!$payments){
         return response()->json("No Payment Was found");
     } else {
         return response()->json($payments);
     }
 }
 
 public function readPayment($id){
     try{
         $payment = Payments::findOrFail($id);
 
         if($payment){
             return response()->json($payment);
         }
         else{
             return response()->json("No Payment Was Found With The ID: ", $id);
         }
     }
     catch(\Exception $e){
         return response()->json([
             'error' => 'Payment Does Not Exist With Such An ID'
         ],400);
     }
 }
 
 public function updatePayment($id, Request $request){
     try{
         $request->validate([
             "user_id"=>"required",
             "booking_id"=>"required",
             "amount"=>"required",
             "payment_status"=>"required",
             "payment_method"=>"required",
         ]);
         $payment = Payments::findOrFail($id);
 
         if($payment){
             $payment->user_id = $request->user_id;
             $payment->booking_id = $request->booking_id;
             $payment->amount = $request->amount;
             $payment->payment_status = $request->payment_status;
             $payment->payment_method = $request->payment_method;
             $payment->save();
 
             return response()->json($payment);
         }
         else{
             return response()->json("No Payment Was Found With The ID: ", $id);
         }
 
     }
     catch(\Exception $e){
         return response()->json([
             'error' => 'Unable to Update Record!'
         ],400);
     }
 }
 
 public function deletePayment($id){
     try{
         $payment = Payments::findOrFail($id);
 
         if($payment){
             Payments::destroy($id);
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
