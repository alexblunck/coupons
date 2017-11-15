<?php

namespace Blunck\Coupons\Exceptions;

class UsedCouponException extends CouponException
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
