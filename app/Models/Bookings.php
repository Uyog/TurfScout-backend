<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "turf_id",
        "duration",
        "total_price",
        "booking_status",
        "booking_time",
    ];
}
