<?php

namespace Blunck\Coupons\Exceptions;

class InvalidCouponCodeException extends CouponException
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
