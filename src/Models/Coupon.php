<?php

namespace Blunck\Coupons\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'discount', 'data', 'is_disposable', 'expires_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['expires_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_disposable' => 'boolean',
        'data' => 'array',
    ];

    /**
     * Many to many user relationship.
     *
     * @return Collection
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('used_at');
    }

    /**
     * Return true if coupon has expired,.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at ? Carbon::now()->gte($this->expires_at) : false;
    }
}
