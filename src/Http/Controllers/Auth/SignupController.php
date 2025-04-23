<?php

namespace JGamboa\NileLaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;


class SignupController extends Controller
{
    public function register(Request $request)
    {
        $userModel = config('auth.providers.users.model');
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email','unique:'. (new $userModel)::class .',email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = (new $userModel)->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        auth()->login($user);

        return response()->json([
            'user' => $user,
        ], 201);
    }
}
