<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turfs extends Model
{
    use HasFactory;

    protected $fillable = [
        "turf_name",
        "location",
        "description",
        "amenities",
        "price_per_hour",
        "availability",
        "image_path",
    ];
}
