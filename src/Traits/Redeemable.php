<?php

namespace Blunck\Coupons\Traits;

use Blunck\Coupons\Coupons;
use Blunck\Coupons\Models\Coupon;

trait Redeemable
{
    /**
     * Redeem coupon code for user.
     *
     * @param string $code
     *
     * @return Coupon|false
     */
    public function redeemCouponCode($code)
    {
        return (new Coupons())::redeem($code, $this);
    }

    /**
     * Many to many coupons relationship.
     *
     * @return Collection
     */
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class)->withPivot('used_at');
    }
}
