<?php

namespace JGamboa\NileLaravelServer\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use JGamboa\NileLaravelServer\Http\Request\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login successful!',
            ]);

        } catch (\Throwable $e) {
            Log::error('[SIGNIN ERROR] ' . $e->getMessage());
            return response()->json([
                'message' => 'Invalid login credentials!',
            ], 401);
        }
    }
}
