<?php

namespace JGamboa\NileLaravelServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use JGamboa\NileLaravelServer\Traits\ResolvesTenant;

class TenantUserController extends Controller
{

    use ResolvesTenant;

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenant = $tenantModel::find($this->getTenantId());

        if (!$tenant) {
            return response()->json(['error' => __('nile-server::messages.tenant_not_found')], 404);
        }

        $perPage = $request->input('per_page', 10);


        $users = $tenant->users();

        if ($perPage === 'all' || (int) $perPage === 0) {
            return response()->json($users->get());
        }

        return response()->json($users->paginate((int) $perPage));
    }

    public function show(string $userId)
    {
        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenant = $tenantModel::find($this->getTenantId());

        if(!$tenant->users()->where('id', $userId)->exists()) {
            return response()->json([
                'message' => __('nile-server::messages.user_not_in_tenant'),
            ], 409);
        }

        $user = $tenant->users()->where('id', $userId)->first();

        return response()->json($user);
    }

    public function update(Request $request, string $userId)
    {
        $userModel = config('auth.providers.users.model');

        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenant = $tenantModel::findOrFail($this->getTenantId());

        if(!$tenant->users()->where('id', $userId)->exists()) {
            return response()->json([
                'message' => __('nile-server::messages.user_not_in_tenant'),
            ], 409);
        }

        $validated = $request->validate([
            'name' => 'required|min:3|string|max:255',
            'status' => 'sometimes|boolean',
            'email' => [
                'required',
                'email',
                Rule::unique((new $userModel)::class, 'email')->ignore($userId)
            ],
        ]);

        $user = $userModel::findOrFail($userId);
        $user->update($validated);

        return response()->json($user);
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
