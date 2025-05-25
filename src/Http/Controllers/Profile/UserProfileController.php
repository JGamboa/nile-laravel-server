<?php
namespace JGamboa\NileLaravelServer\Http\Controllers\Profile;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Actualiza el perfil del usuario autenticado en la sesión.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $userModel = config('auth.providers.users.model');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique((new $userModel)::class, 'email')->ignore($user->id)
            ],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->save();

        return response()->json([
            'message' => __('nile-server::messages.profile_updated'),
        ]);
    }
}
