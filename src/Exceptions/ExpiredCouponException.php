<?php

namespace Blunck\Coupons\Exceptions;

class ExpiredCouponException extends CouponException
{
    /**
     * @var string
     */
    protected $message = 'Coupon has expired.';

    /**
     * @var string
     */
    protected $code = 403;
}
