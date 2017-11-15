<?php

namespace Blunck\Coupons\Traits;

use Blunck\Coupons\Models\Coupon;
use Coupons;

trait Redeemable
{
    /**
     * Redeem coupon for user.
     *
     * @param Coupon $coupon
     *
     * @return Coupon|false
     */
    public function redeemCoupon(Coupon $coupon)
    {
        return Coupons::redeem($coupon, $this);
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
