<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function currentUser(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateName(Request $request, $id)
    {   
        $request->validate([
            'name' => 'required',
        ]);

        $user = User::findOrFail($id);
        if($user){
            $user->update([
                'name'=>$request->name,
            ]);
        }
        else{
            return response()->json("User doesn't exist With ID: ", $id);
        }


        return response()->json($user);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->delete();

        return response()->json(null, 204);
    }

}

