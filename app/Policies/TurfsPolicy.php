<?php

namespace App\Policies;

use App\Models\Turf;
use App\Models\Turfs;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TurfsPolicy
{
    use HandlesAuthorization;

    // public function createTurf(User $user)
    // {
    //     return $user->hasPermissionTo('create turfs');
    // }

    // public function updateTurf(User $user, Turfs $turf)
    // {
    //     return $user->hasPermissionTo('update turfs') && $user->id === $turf->user_id;
    // }

    // public function deleteTurf(User $user, Turfs $turf)
    // {
    //     return $user->hasPermissionTo('delete turfs') && $user->id === $turf->user_id;
    // }
}
