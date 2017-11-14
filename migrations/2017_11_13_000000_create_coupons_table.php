<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();

            $table->string('code', 32)->unique();
            $table->double('discount', 10, 2)->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_disposable')->default(true);
        });

        Schema::create('coupon_user', function (Blueprint $table) {
            $table->timestamp('used_at');

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('coupon_id');

            $table->primary(['user_id', 'coupon_id']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('coupon_id')->references('id')->on('coupons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('coupon_user');
    }
}
