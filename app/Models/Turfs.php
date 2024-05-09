<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turfs extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "location",
        "description",
        "image_url",
        "price",
        "creator_id"
    ];
}
