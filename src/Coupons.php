<?php

namespace Blunck\Coupons;

use Carbon\Carbon;
use Blunck\Coupons\Models\Coupon;
use Blunck\Coupons\Exeptions\InvalidCouponCodeException;
use Blunck\Coupons\Exeptions\AlreadyUsedCouponException;

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
     * Check if a given coupon code is valid.
     *
     * @param string $code
     *
     * @return Coupon|false
     */
    public function check($code)
    {
        $coupon = Coupon::where('code', $code)->first();

        // Check if exists
        if (!$coupon) {
            throw new InvalidCouponCodeException();
        }

        // Check if expired
        if ($coupon->isExpired()) {
            return false;
        }

        // Check if disposable & used
        if ($coupon->is_disposable && $coupon->users()->exists()) {
            return false;
        }

        return $coupon;
    }

    /**
     * Redeem a given coupon code for a specific user.
     *
     * @param Coupon $code
     * @param User   $user
     *
     * @return Coupon|false
     */
    public function redeem(Coupon $code, $user)
    {
        try {
            $coupon = $this->check($code);

            // Check if coupon is valid
            if ($coupon) {
                // Check if user has already used coupon
                if ($this->isSecondUsageAttempt($coupon, $user)) {
                    throw new AlreadyUsedCouponException();
                }

                $coupon->users()->attach($user->id, [
                    'used_at' => Carbon::now(),
                ]);

                return $coupon;
            }
        } catch (InvalidCouponCodeException $exception) {
        }

        return false;
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
