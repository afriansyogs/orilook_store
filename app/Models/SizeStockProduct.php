<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SizeStockProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'size',
        'stock',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
