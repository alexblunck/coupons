<?php

namespace Blunck\Coupons;

use Carbon\Carbon;
use Blunck\Coupons\Models\Coupon;
use Blunck\Coupons\Exceptions\CouponException;
use Blunck\Coupons\Exceptions\InvalidCouponCodeException;
use Blunck\Coupons\Exceptions\ExpiredCouponException;
use Blunck\Coupons\Exceptions\UsedCouponException;

class Coupons
{
    public function __construct()
    {
    }

    /**
     * Generate & store a new coupon.
     *
     * @param bool  $is_disposable - If true coupon can only be redeemed once
     * @param float $discount      - Amount of money
     * @param int   $expires_in    - Days in which coupon expires
     * @param array $data          - Additional data
     *
     * @return Coupon
     */
    public function create($is_disposable, $discount, $expires_in = null, array $data = [])
    {
        $coupon = Coupon::create([
            'code' => $this->generate(),
            'is_disposable' => $is_disposable,
            'discount' => $discount,
            'expires_at' => $expires_in ? Carbon::now()->addDays($expires_in) : null,
            'data' => $data,
        ]);

        return $coupon;
    }

    /**
     * Check if a given coupon code is valid &
     * return coupon.
     *
     * @param Coupon $coupon
     * @param User   [$user] - Check if user has already redeemed coupon
     *
     * @return Coupon
     */
    public function check($code, $user = null)
    {
        $coupon = Coupon::where('code', $code)->first();

        // Check if exists
        if (!$coupon) {
            throw new InvalidCouponCodeException();
        }

        // Check if expired
        if ($coupon->isExpired()) {
            throw new ExpiredCouponException();
        }

        // Check if disposable & used
        if ($coupon->is_disposable && $coupon->users()->exists()) {
            throw new UsedCouponException();
        }

        // Check id user has already redeemed coupon
        if ($user && $this->isSecondUsageAttempt($coupon, $user)) {
            throw new UsedCouponException();
        }

        return $coupon;
    }

    /**
     * Redeem a given coupon for a specific user.
     *
     * @param Coupon $coupon
     * @param User   $user
     *
     * @return Coupon|false
     */
    public function redeem(Coupon $coupon, $user)
    {
        try {
            $coupon = $this->check($coupon->code);

            // Create pivot table record
            $coupon->users()->attach($user->id, [
                'used_at' => Carbon::now(),
            ]);
        } catch (CouponException $e) {
            return false;
        }

        return $coupon;
    }

    /**
     * Return a radomly generated code in following
     * format: xxxx-xxxx-xxxx.
     *
     * @return string
     */
    private function generate()
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $parts = [];

        for ($i = 0; $i < 3; ++$i) {
            $parts[] = substr(str_shuffle($characters), 0, 4);
        }

        $code = implode($parts, '-');

        return $code;
    }

    /**
     * Return true if a given coupon was already redeemed by
     * a specific user.
     *
     * @param Coupon $coupon
     * @param User   $user
     *
     * @return bool
     */
    private function isSecondUsageAttempt(Coupon $coupon, $user)
    {
        return $coupon->users()->wherePivot('user_id', $user->id)->exists();
    }
}
