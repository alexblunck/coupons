<?php

namespace Blunck\Coupons;

use Illuminate\Support\ServiceProvider;

class CouponServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('coupons', function ($app) {
            return new Coupons();
        });
    }
}
