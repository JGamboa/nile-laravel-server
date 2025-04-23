<?php

declare(strict_types=1);

namespace JGamboa\NileLaravelServer\Traits;

use Illuminate\Support\Facades\App;

trait ResolvesTenant
{
    public function getTenantId()
    {
        return App::make('tenant_id');
    }
}
