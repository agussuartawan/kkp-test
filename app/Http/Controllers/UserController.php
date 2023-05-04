<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Ship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function getUserVerified()
    {
        return User::where('email_verified_at', '!=', null)
            ->whereKeyNot(auth()->user()->id)
            ->get();
    }

    public function verifUser(User $user, string $verif)
    {
        if ($verif != 'reject' && $verif != 'verif') abort(404);
        if ($verif == 'verif') $user->is_approved = true;
        if ($verif == 'reject') $user->is_approved = false;
        $user->save();
        return $user;
    }

    public function delete(Ship $ship)
    {
        return $ship->delete();
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        return $user->update($data);
    }

    public function updateMe(UpdateUserRequest $request)
    {
        return $this->update(User::find(auth()->user()->id), $request);
    }


}
