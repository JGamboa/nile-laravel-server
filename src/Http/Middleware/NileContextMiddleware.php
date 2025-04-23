<?php

declare(strict_types=1);

namespace JGamboa\NileLaravelServer\Http\Middleware;

use App;
use Closure;
use Illuminate\Support\Facades\Auth;

final class NileContextMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->hasHeader('x-nile-tenant-id')) {
            if( ! $this->userHasAccessToTenant($request->header('x-nile-tenant-id')) ) {
                abort(403, __('messages.tenant_forbidden'));
            }

            $request->attributes->set('nile_tenant_id', $request->header('x-nile-tenant-id'));
            App::instance('tenant_id', $request->header('x-nile-tenant-id'));
        }

        return $next($request);
    }

    /**
     *
     * @param string $tenantId
     * @return bool
     */
    protected function userHasAccessToTenant(string $tenantId): bool
    {
        $user = Auth::user();

        if ($user) {
            return $user->tenants()->where('id', $tenantId)->exists();
        }

        return false;
    }

}
