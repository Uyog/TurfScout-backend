<?php

namespace App\Models;


use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurfCreator extends SpatieRole
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::created(function ($role){
            $role->permissions([
                'create turfs',
                'edit turfs',
                'delete turfs',
            ]);
        });
    }
}
