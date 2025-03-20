<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Review;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'payment_id',
        'city_id',
        'size_stock_product_id',
        'voucher_id',
        'addres',
        'payment_proof',
        'qty',
        'total_amount',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function payment() {
        return $this->belongsTo(Payment::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function sizeStock()
    {
        return $this->belongsTo(SizeStockProduct::class, 'size_stock_product_id', 'id');
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function review()
{
    return $this->hasOne(Review::class);
}

protected static function boot()
    {
        parent::boot();

        static::created(function ($order) {
            $sizeStock = \App\Models\SizeStockProduct::find($order->size_stock_product_id);

            if ($sizeStock) {
                // Pastikan stok tidak negatif
                $sizeStock->stock = max(0, $sizeStock->stock - $order->qty);
                $sizeStock->save();
            }
        });
    }

}
