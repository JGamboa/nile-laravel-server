<?php

declare(strict_types=1);

namespace JGamboa\NileLaravelServer\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class NileUserModel extends Authenticatable
{
    use HasUuids;
    use HasApiTokens;
    use SoftDeletes;

    protected $table = 'users.users';

    const string CREATED_AT = 'created';
    const string UPDATED_AT = 'updated';
    const string DELETED_AT = 'deleted';

    public function tenants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        $tenantModel = config('nile-laravel-server.models.tenant');
        return $this->belongsToMany((new $tenantModel)::class, 'users.tenant_users', 'user_id', 'tenant_id');
    }

    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified);
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified' => $this->freshTimestamp(),
        ])->save();
    }
}
