<?php

namespace Blunck\Coupons\Exeptions;

use Exception;

class InvalidCouponCodeException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Invalid coupon code.';

    /**
     * @var string
     */
    protected $code = 404;
}
