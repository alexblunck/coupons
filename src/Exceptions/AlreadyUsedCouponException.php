<?php

namespace Blunck\Coupons\Exeptions;

use Exception;

class AlreadyUsedCouponException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'The coupon has already been used by the user.';

    /**
     * @var string
     */
    protected $code = 403;
}
