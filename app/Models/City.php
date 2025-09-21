<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'province_id',
        'city_name',
        'shipping_price'
    ];

    public function province() {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
