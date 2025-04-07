<?php

declare(strict_types=1);

namespace JGamboa\NileLaravelServer\Http\Middleware;

use App;
use Closure;

final class NileContextMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->hasHeader('x-nile-tenant-id')) {
            $request->attributes->set('nile_tenant_id', $request->header('x-nile-tenant-id'));
            App::instance('tenant_id', $request->header('x-nile-tenant-id'));
        }

        return $next($request);
    }
}
