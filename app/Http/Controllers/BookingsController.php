<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use Illuminate\Http\Request;
use Carbon\Carbon;


class BookingsController extends Controller
{
    public function createBooking(Request $request){
        $request->validate([
        "turf_id"=>"required|exists:turfs,id",
        "duration"=>"required",
        "total_price"=>"required|numeric",
        "booking_status"=>"required",
        "booking_time"=>"required",
        ]);

        $bookingTime = Carbon::createFromFormat('h:i A', $request->input('booking_time'));
    
        $bookedTimeSlots = Bookings::where('booking_time', $bookingTime)->get();
        if ($bookedTimeSlots->count() > 0) {
            return response()->json(['error' => 'This time slot is already booked.'], 400);
        }
    

        $booking = Bookings::create([
         "user_id" =>$request->user()->id,
         "turf_id" =>$request->turf_id,
         "duration" =>$request->duration,
         "total_price" =>$request->total_price,
         "booking_status" =>"pending",
         "booking_time"=>$bookingTime
        ]);
 
        return response()->json($booking);
 }
 
 public function readAllBookings(Request $request){
     //$bookings = Bookings::all();
    //  $bookings = Bookings::join('users','bookings.user_id', '=', 'id')->
    //  select('bookings.*','users.name as name')->get();

    $bookings = Bookings::where('user_id', $request->user()->id)
                            ->join('turfs','bookings.turf_id', '=', 'turfs.id')
                           ->join('users', 'bookings.user_id', '=', 'users.id') 
                            ->select('bookings.*','turfs.turf_name as turf_name', 'users.name as user_name', 'users.email as user_email' )->get();


     if(!$bookings){
         return response()->json("No Booking Was found");
     } else {
         return response()->json($bookings);
     }
 }
 
 public function readBooking($id){
     try{
         $booking = Bookings::findOrFail($id);
 
         if($booking){
             return response()->json($booking);
         }
         else{
             return response()->json("No Booking Was Found With The ID: ", $id);
         }
     }
     catch(\Exception $e){
         return response()->json([
             'error' => 'Booking Does Not Exist With Such An ID'
         ],400);
     }
 }
 
 public function updateBooking($id, Request $request){
     try{
         $request->validate([
            "user_id"=>"required",
            "turf_id"=>"required",
            "duration"=>"required",
            "total_price"=>"required",
            "booking_status"=>"required",
            "booking_time"=>"required",
         ]);
         $booking = Bookings::findOrFail($id);
 
         if($booking){
             $booking->user_id = $request->user_id;
             $booking->turf_id = $request->turf_id;
             $booking->duration = $request->duration;
             $booking->total_price = $request->total_price;
             $booking->booking_status = $request->booking_status;
             $booking-> booking_time = $request-> booking_time;
             $booking->save();
 
             return response()->json($booking);
         }
         else{
             return response()->json("No Booking Was Found With The ID: ", $id);
         }
 
     }
     catch(\Exception $e){
         return response()->json([
             'error' => 'Unable to Update Record!'
         ],400);
     }
 }
 
 public function deleteBooking($id){
     try{
         $booking = Bookings::findOrFail($id);
 
         if($booking){
             Bookings::destroy($id);
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