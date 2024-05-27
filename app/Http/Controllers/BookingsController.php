<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\BookingMadeNotification;
use Illuminate\Validation\UnauthorizedException;


class BookingsController extends Controller
{
    public function createBooking(Request $request)
    {

        if (!$request->user()) {
            throw new UnauthorizedException('You must be logged in to book a turf!');
        }

        $request->validate([
            "turf_id" => "required|exists:turfs,id",
            "booking_time" => "required",
            "ball" => "numeric",
            "bib" => "numeric",
        ]);




        $additionalCharges = ($request->input('ball') * 500) + ($request->input('bib') * 200);

        $totalPrice = 2500 + $additionalCharges;
        $bookingTime = Carbon::createFromFormat('h:i A', $request->input('booking_time'));


        $duration = 90;


        $endTime = $bookingTime->copy()->addMinutes($duration);



        $existingBooking = Bookings::where(function ($query) use ($bookingTime, $endTime) {
            $query->whereBetween('booking_time', [$bookingTime, $endTime])
                ->orWhereBetween('booking_end_time', [$bookingTime, $endTime]);
        })->where('booking_status', '<>', 'cancelled')
            ->where('booking_status', '<>', 'rejected')
            ->where('booking_status', '<>', 'completed')
            ->where('booking_status', '<>', 'expired')
            ->exists();

        if ($existingBooking) {
            return response()->json(['error' => 'This time slot is already booked.'], 400);
        }

        $booking = Bookings::create([
            "user_id" => $request->user()->id,
            "turf_id" => $request->turf_id,
            "duration" => $duration,
            "total_price" => $totalPrice,
            "booking_status" => "pending",
            "booking_time" => $bookingTime,
            "booking_end_time" => $endTime,
            "ball" => $request->ball,
            "bib" => $request->bib,
        ]);


        $turfCreator = $booking->turf->creator;
        $turfCreator->notify(new BookingMadeNotification($booking));

        return response()->json($booking);
    }

    public function submitRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'nullable|string|max:255',
        ]);

        $booking = Bookings::findOrFail($id);


        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        $booking->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json(['message' => 'Rating and review submitted successfully']);
    }
}
