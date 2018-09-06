# coupons

[![Latest Version on Packagist](https://img.shields.io/packagist/v/blunck/coupons.svg?style=flat-square)](https://packagist.org/packages/blunck/coupons)

Coupon generator for Laravel 5.

## Installation

You can install the package via composer:

```bash
composer require blunck/coupons
```

The package includes a migration to create a `coupons` & `coupon_user` table:

```bash
# Publish migration file
php artisan vendor:publish --provider="Blunck\Coupons\CouponServiceProvider"

# Run migration
php artisan migrate
```

Add `Redeemable` trait to `User` model:
```php
use Blunck\Coupons\Traits\Redeemable;

class User {
    use Redeemable;

    // ...
}
```

## Usage

#### Retrieve / Check if a coupon code is valid
```php
try {
    $coupon = Coupons::check('AAAA-BBBB-CCCC');
} catch (CouponException $e) {
    //
}
```

You can optionally pass a user instance as the 2nd argument to `Coupons::check` to check if user has already redeemed a non disposable coupon.

#### Redeem coupon
```php
$user->redeemCoupon($coupon);
```

Redeeming a coupon adds a record to the `coupon_user` pivot table.

#### Create Coupon
```php
/**
 * Disposable coupons can only be used onece.
 *
 * @var boolean
 */
$is_disposable = true;

/**
 * Coupon discount.
 *
 * @var float
 */
$discount = 10.50;

/**
 * Days from now when coupon expires. If null
 * coupon never expires.
 *
 * @var integer|null
 */
$expires_in = 30;

/**
 * Additional data.
 *
 * @var array
 */
$data = ['note' => 'lorem ipsum'];

$coupon = Coupons::create($is_disposable, $discount, $expires_in, $data);
$code = $coupon->code;
```

## Acknowledgments
Architecture inspired by [laravel-promocodes](https://github.com/zgabievi/laravel-promocodes)
