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
}
