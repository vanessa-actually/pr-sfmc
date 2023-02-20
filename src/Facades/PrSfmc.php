<?php

namespace Dmgroup\PrSfmc\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dmgroup\PrSfmc\PrSfmc
 */
class PrSfmc extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Dmgroup\PrSfmc\PrSfmc::class;
    }
}
