<?php

namespace JGamboa\NileLaravelServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SystemUserController extends Controller
{
    protected string $userModel;

    public function __construct()
    {
        $this->userModel = config('auth.providers.users.model');
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = ($this->userModel)::query();

        if ($perPage === 'all') {
            return response()->json($query->get());
        }

        return response()->json($query->paginate((int)$perPage));
    }

    public function show(string $id)
    {
        $user = ($this->userModel)::findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{7,15}$/', 'max:16'],
            'is_active' => 'sometimes|boolean',
        ]);

        $user = ($this->userModel)::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, string $id)
    {
        $user = ($this->userModel)::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes', 'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{7,15}$/', 'max:16'],
            'is_active' => 'sometimes|boolean',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    public function destroy(string $id)
    {
        $user = ($this->userModel)::findOrFail($id);
        $user->delete();

        return response()->json(['message' => __('nile-server::messages.user_deleted'),]);
    }
}
