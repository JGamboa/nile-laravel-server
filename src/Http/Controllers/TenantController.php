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
        $data = $request->only(['id', 'name']);
        $userId = $request->user()->id;

        \App::instance('tenant_id', $data["id"]);

        $tenants = DB::select(
            'INSERT INTO tenants (id, name) VALUES (?, ?) RETURNING *',
            [$data['id'], $data['name']]);

        Log::info('/*****************************************/');
        Log::info('created tenant: '.print_r($tenants, true));
        Log::info('/*****************************************/');

        DB::table('tenant_users')->insert([
            'tenant_id' => $tenants[0]->id,
            'user_id' => $userId,
        ]);

        return response()->json($tenants);
    }

    public function list(Request $request)
    {
        $userId = $request->user()->id;

        $tenants = DB::table('tenants')
            ->join('tenant_users', 'tenants.id', '=', 'tenant_users.tenant_id')
            ->where('tenant_users.user_id', $userId)
            ->get();

        return response()->json($tenants);
    }
}
