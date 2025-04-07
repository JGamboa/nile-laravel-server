<?php

declare(strict_types=1);

namespace JGamboa\NileLaravelServer\Scopes;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Container\BindingResolutionException;

final class TenantScope implements Scope
{
    /**
     * @throws BindingResolutionException
     */
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = App::make('tenant_id');
        if ($tenantId) {
            $builder->where($model->getTable().'.tenant_id', $tenantId);
        }
    }
}
