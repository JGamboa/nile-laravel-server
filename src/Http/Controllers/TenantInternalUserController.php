<?php

namespace JGamboa\NileLaravelServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use JGamboa\NileLaravelServer\Notifications\UserCreatedNotification;
use JGamboa\NileLaravelServer\Traits\ResolvesTenant;

class TenantInternalUserController extends Controller
{
    use ResolvesTenant;

    protected string $userModel;

    public function __construct()
    {
        $this->userModel = config('auth.providers.users.model');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique((new $this->userModel)::class, 'email')
            ],
        ]);

        $generatedPassword = Str::random(12);

        $user = ($this->userModel)::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($generatedPassword),
        ]);

        $user->tenants()->attach($this->getTenantId());

        $user->notify(new UserCreatedNotification($generatedPassword));

        return response()->json($user, 201);
    }

    public function destroy(string $userId)
    {
        $tenantModel = config('nile-laravel-server.models.tenant');
        $tenant = $tenantModel::findOrFail($this->getTenantId());

        if(!$tenant->users()->where('id', $userId)->exists()) {
            return response()->json([
                'message' => __('nile-server::messages.user_not_in_tenant'),
            ], 409);
        }

        $tenant->users()->detach($userId);

        return response()->json(['message' =>__('nile-server::messages.user_deleted')]);
    }
}
