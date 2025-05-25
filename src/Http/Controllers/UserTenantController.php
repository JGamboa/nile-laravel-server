<?php

namespace JGamboa\NileLaravelServer\Http\Controllers;

use Illuminate\Routing\Controller;

class UserTenantController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            return response()->json($user->tenants);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
