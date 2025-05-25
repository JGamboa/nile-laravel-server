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
        if (App::bound('tenant_id')) {
            $tenantId = App::make('tenant_id');
            $builder->where($model->getTable().'.tenant_id', $tenantId);
        }
    }

    public function extend(Builder $builder)
    {
        if (App::bound('tenant_id')) {
            $tenantId = App::make('tenant_id');

            foreach (['update', 'delete'] as $method) {
                $builder->macro($method, function (Builder $builder, ...$args) use ($tenantId, $method) {
                    $builder->where($builder->getModel()->getTable().'.tenant_id', $tenantId);
                    return $builder->{$method.'WithoutTenant'}(...$args);
                });
            }
        }
    }
}
