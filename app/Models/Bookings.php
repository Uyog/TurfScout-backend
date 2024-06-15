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
        'booking_time',
        'booking_end_time',
        'ball',
        'bib',
        'pitch_number',
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

    public function rating()
    {
        return $this->hasOne(Ratings::class);
    }

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
