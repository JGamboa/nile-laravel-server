<?php

namespace JGamboa\NileLaravelServer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JGamboa\NileLaravelServer\NileLaravelServer
 */
class NileLaravelServer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JGamboa\NileLaravelServer\NileLaravelServer::class;
    }
}
