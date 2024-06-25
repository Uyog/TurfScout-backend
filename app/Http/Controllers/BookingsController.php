<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\Ratings;
use App\Models\Turfs;
use Carbon\Carbon;
use App\Notifications\BookingMadeNotification;
use Illuminate\Http\Request;
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
        $bookingTime = $this->parseBookingTime($timeString);

        if (!$bookingTime) {
            return response()->json(['error' => 'Invalid booking time format.'], 400);
        }

        $additionalCharges = ($request->input('ball') * 500) + ($request->input('bib') * 200);
        $totalPrice = 2500 + $additionalCharges;
        $duration = 90;
        $endTime = $bookingTime->copy()->addMinutes($duration);
        $pitchNumber = $request->input('pitch_number');

        if ($this->isBookingSlotTaken($request->turf_id, $pitchNumber, $bookingTime, $endTime)) {
            return response()->json(['error' => 'This time slot is already booked.'], 400);
        }

        $turf = Turfs::find($request->turf_id);
        if (!$turf || $pitchNumber > $turf->number_of_pitches) {
            return response()->json(['error' => 'Invalid pitch number or turf not found'], 400);
        }

        $initialStatus = $this->determineInitialStatus($bookingTime, $endTime);

        $booking = Bookings::create([
            "user_id" => $request->user()->id,
            "turf_id" => $request->turf_id,
            "duration" => $duration,
            "total_price" => $totalPrice,
            "booking_status" => $initialStatus,
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

    private function parseBookingTime($timeString)
    {
        try {
            if (strpos($timeString, 'AM') !== false || strpos($timeString, 'PM') !== false) {
                return Carbon::createFromFormat('h:i A', $timeString);
            } else {
                return Carbon::createFromFormat('H:i', $timeString);
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    private function isBookingSlotTaken($turfId, $pitchNumber, $bookingTime, $endTime)
    {
        return Bookings::where('turf_id', $turfId)
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
    }

    private function determineInitialStatus($bookingTime, $endTime)
    {
        $currentTime = Carbon::now();
        if ($currentTime->between($bookingTime, $endTime)) {
            return 'in progress';
        } elseif ($currentTime->gt($endTime)) {
            return 'completed';
        } else {
            return 'pending';
        }
    }

    public function submitRating(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            throw new UnauthorizedException('You must be logged in to rate a booking.');
        }

        $booking = Bookings::find($id);
        if (!$booking || $booking->user_id !== $user->id) {
            return response()->json(['error' => $booking ? 'You are not authorized to rate this booking.' : 'Booking not found.'], $booking ? 403 : 404);
        }

        if (Carbon::now()->isBefore(Carbon::parse($booking->booking_end_time))) {
            return response()->json(['error' => 'You can only rate after the booking duration has ended.'], 400);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $rating = Ratings::updateOrCreate(
            ['booking_id' => $booking->id, 'user_id' => $user->id, 'turf_id' => $booking->turf_id],
            ['rating' => $request->rating, 'review' => $request->review]
        );

        $booking->update(['booking_status' => 'rated']);

        return response()->json($rating, 201);
    }

    public function getUserBookings(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            throw new UnauthorizedException('You must be logged in to view bookings.');
        }

        $bookings = Bookings::where('user_id', $user->id)->get();

        return response()->json($bookings);
    }

    public function cancelBooking(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            throw new UnauthorizedException('You must be logged in to cancel a booking.');
        }

        $booking = Bookings::find($id);
        if (!$booking || $booking->user_id !== $user->id) {
            return response()->json(['error' => $booking ? 'You are not authorized to cancel this booking.' : 'Booking not found.'], $booking ? 403 : 404);
        }

        $booking->update(['booking_status' => 'cancelled']);

        return response()->json(['message' => 'Booking cancelled successfully.'], 200);
    }

    public function updateCompletedBookings()
    {
        $bookings = Bookings::where('booking_end_time', '<', Carbon::now())
            ->whereNotIn('booking_status', ['completed', 'cancelled', 'rejected', 'expired'])
            ->get();

        foreach ($bookings as $booking) {
            $booking->update(['booking_status' => 'completed']);
        }

        return response()->json(['message' => 'Booking statuses updated to completed successfully.'], 200);
    }

    public function updateInProgressBookings()
    {
        $currentTime = Carbon::now();

        $bookings = Bookings::where('booking_time', '<=', $currentTime)
            ->where('booking_end_time', '>=', $currentTime)
            ->where('booking_status', 'pending')
            ->get();

        foreach ($bookings as $booking) {
            $booking->update(['booking_status' => 'in progress']);
        }

        return response()->json(['message' => 'Booking statuses updated to in progress successfully.'], 200);
    }
}
