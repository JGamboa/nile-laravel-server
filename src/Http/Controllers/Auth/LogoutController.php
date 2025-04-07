<?php

namespace JGamboa\NileLaravelServer\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->noContent();
        } catch (\Throwable $e) {
            Log::error('[SIGNOUT ERROR] ' . $e->getMessage());
            return response()->json(['error' => 'Sign-out proxy failed'], 500);
        }
    }
}
