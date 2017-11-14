<?php

namespace Blunck\Coupons\Facades;

use Illuminate\Support\Facades\Facade;

class Coupons extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'coupons';
    }
}
