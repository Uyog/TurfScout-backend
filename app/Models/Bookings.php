<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Bookings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'turf_id',
        'duration',
        'total_price',
        'booking_status',
        'rating',
        'review',
        'booking_time',
        'booking_end_time',
        'ball',
        'bib',
    ];

    protected $casts = [
        'booking_time' => 'datetime',
        'booking_end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function turf()
    {
        return $this->belongsTo(Turfs::class);
    }

    // Accessors and Mutators for handling Carbon instances for booking_time and booking_end_time
    public function getBookingTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getBookingEndTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function setBookingTimeAttribute($value)
    {
        $this->attributes['booking_time'] = Carbon::parse($value);
    }

    public function setBookingEndTimeAttribute($value)
    {
        $this->attributes['booking_end_time'] = Carbon::parse($value);
    }
}
