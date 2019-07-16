<?php

namespace Peterzaccha\JoQueryGenerator\Facades;

use Illuminate\Support\Facades\Facade;

class JoJoinFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'jojoin';
    }
}
