<?php

namespace JGamboa\NileLaravelServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use JGamboa\NileLaravelServer\Traits\ResolvesTenant;

class TenantUserController extends Controller
{

    use ResolvesTenant;

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenant = $tenantModel::find($this->getTenantId());

        if (!$tenant) {
            return response()->json(['error' => __('nile-server::messages.tenant_not_found')], 404);
        }

        $users = $tenant->users;

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users.users,id',
        ]);

        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenant = $tenantModel::findOrFail($this->getTenantId());

        if($tenant->users()->where('id', $validated['user_id'])->exists()) {
            return response()->json([
                'message' => __('nile-server::messages.user_already_in_tenant'),
            ], 409);
        }

        $tenant->users()->attach($validated['user_id']);

        return response()->json([
            'message' => __('nile-server::messages.user_added_to_tenant'),
        ]);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users.users,id',
        ]);

        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenant = $tenantModel::findOrFail($this->getTenantId());

        if(!$tenant->users()->where('id', $validated['user_id'])->exists()) {
            return response()->json([
                'message' => __('nile-server::messages.user_not_in_tenant'),
            ], 409);
        }

        $tenant->users()->detach($validated['user_id']);

        return response()->json([
            'message' => __('nile-server::messages.user_removed_from_tenant'),
        ]);
    }
}
