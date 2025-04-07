<?php

namespace JGamboa\NileLaravelServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class UserTenantController extends Controller
{
    public function list(Request $request)
    {
        $userId = $request->user()->id;
        try {
            $tenants = DB::select("
                SELECT DISTINCT
                    t.id,
                    t.name
                FROM public.tenants t
                JOIN users.tenant_users tu ON t.id = tu.tenant_id
                WHERE tu.user_id = ?
                  AND tu.deleted IS NULL
                  AND t.deleted IS NULL
            ", [$userId]);

            if (empty($tenants)) {
                return response()->json([], 404);
            }

            Cookie::queue('tenants', $tenants);

            return response()->json($tenants);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
