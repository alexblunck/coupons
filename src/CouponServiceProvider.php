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
        $this->publishes([
            __DIR__.'/../migrations/create_coupons_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_coupons_table.php'),
        ], 'migrations');
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
