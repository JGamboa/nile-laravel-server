<?php

declare(strict_types=1);

namespace JGamboa\NileLaravelServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TenantController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->only(['name']);
        $userId = $request->user()->id;

        $tenants = DB::select(
            'INSERT INTO tenants (name) VALUES (?) RETURNING *', [$data['name']]);

        Log::info('/*****************************************/');
        Log::info('created tenant: '.print_r($tenants, true));
        Log::info('/*****************************************/');

        DB::table('users.tenant_users')->insert([
            'tenant_id' => $tenants[0]->id,
            'user_id' => $userId,
        ]);

        return response()->json($tenants[0]);
    }

    public function index()
    {
        $user = auth()->user();
        return response()->json($user->tenants);
    }
}
