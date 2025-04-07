<?php

declare(strict_types=1);

namespace JGamboa\NileLaravelServer\Services;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TenantDB
{
    public static function run(Closure $callback)
    {
        $tenantId = app()->bound('tenant_id') ? app('tenant_id') : null;

        return DB::transaction(function () use ($callback, $tenantId) {
            Log::info('Executing query with tenant: '.($tenantId ?? 'none'));

            if ($tenantId) {
                DB::statement('set local nile.tenant_id = ?', [$tenantId]);
            }

            return $callback(DB::connection());
        });
    }
}
