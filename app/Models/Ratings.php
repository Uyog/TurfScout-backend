<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'turf_id',
        'rating',
        'review'
    ];
    public function booking()
    {
        return $this->belongsTo(Bookings::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function turf()
    {
        return $this->belongsTo(Turfs::class);
    }
}
