<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use Illuminate\Http\Request;
use App\Models\Turfs;
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
            "booking_time" => [
                'required',
                'regex:/^(?:[01]\d|2[0-3]):[0-5]\d(?:\s?[APMapm]{2})?$/'
            ],
            "ball" => "numeric|min:0",
            "bib" => "numeric|min:0",
            "pitch_number" => "required|integer|min:1",
        ]);

        $timeString = $request->input('booking_time');

        try {
            if (strpos($timeString, 'AM') !== false || strpos($timeString, 'PM') !== false) {
                $bookingTime = Carbon::createFromFormat('h:i A', $timeString);
            } else {
                $bookingTime = Carbon::createFromFormat('H:i', $timeString);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid booking time format.'], 400);
        }

        $additionalCharges = ($request->input('ball') * 500) + ($request->input('bib') * 200);
        $totalPrice = 2500 + $additionalCharges;
        $duration = 90;
        $endTime = $bookingTime->copy()->addMinutes($duration);

        $pitchNumber = $request->input('pitch_number');


        $existingBooking = Bookings::where('turf_id', $request->turf_id)
            ->where('pitch_number', $pitchNumber)
            ->where(function ($query) use ($bookingTime, $endTime) {
                $query->whereBetween('booking_time', [$bookingTime, $endTime])
                    ->orWhereBetween('booking_end_time', [$bookingTime, $endTime])
                    ->orWhere(function ($query) use ($bookingTime, $endTime) {
                        $query->where('booking_time', '<=', $bookingTime)
                            ->where('booking_end_time', '>=', $endTime);
                    });
            })
            ->whereNotIn('booking_status', ['cancelled', 'rejected', 'completed', 'expired'])
            ->exists();

        if ($existingBooking) {
            return response()->json(['error' => 'This time slot is already booked.'], 400);
        }

        $turf = Turfs::find($request->turf_id);
        if (!$turf) {
            return response()->json(['error' => 'Turf not found.'], 404);
        }

        if ($pitchNumber > $turf->number_of_pitches) {
            return response()->json(['error' => 'Invalid pitch number'], 400);
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
            "pitch_number" => $pitchNumber,
        ]);

        if ($turf->creator) {
            $turf->creator->notify(new BookingMadeNotification($booking));
        }

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
