<?php

declare(strict_types=1);

namespace JGamboa\NileLaravelServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TenantController extends Controller
{

    public function index()
    {
        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenants = $tenantModel::all();
        return response()->json($tenants);
    }

    public function show(string $tenantId)
    {
        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenant = $tenantModel::findOrFail($tenantId);
        return response()->json($tenant);
    }

    public function store(Request $request)
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

    public function update(Request $request, string $tenantId)
    {
        $data = $request->only(['name']);

        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenant = $tenantModel::findOrFail($tenantId);

        $tenant->update($data);

        return response()->json($tenant);
    }

    public function destroy(string $tenantId)
    {
        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenant = $tenantModel::findOrFail($tenantId);

        $tenant->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
