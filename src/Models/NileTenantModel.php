<?php

declare(strict_types=1);


namespace JGamboa\NileLaravelServer\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

abstract class NileTenantModel extends Model
{
    use HasUuids;
    use SoftDeletes;

    const string CREATED_AT = 'created';

    const string UPDATED_AT = 'modified';

    const string DELETED_AT = 'deleted';

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        $userModel = 'App\Models\User';
        return $this->belongsToMany((new $userModel)::class, 'users.tenant_users', 'tenant_id', 'user_id');
    }
}
