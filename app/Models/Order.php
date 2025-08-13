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
        'size_stock_product_id',
        'voucher_id',
        'order_code', // ID unik pesanan
        'payment_id',
        'payment_token', 
        'payment_status', 
        'city_id',
        'addres',
        'qty',
        'total_amount',
        'status', 
        'shipping_status', 
        'tracking_number', 
        'order_item', 
    ];

    protected $casts = [
        'order_item' => 'array',
    ];

    public function getOrderItemsWithDetails()
    {
        $orderItems = json_decode($this->order_item, true);
        
        if (empty($orderItems)) {
            return collect([]);
        }
        
        // Ekstrak semua product_id dan size_stock_product_id
        $productIds = collect($orderItems)->pluck('product_id')->unique()->toArray();
        $sizeStockIds = collect($orderItems)->pluck('size_stock_product_id')->unique()->toArray();
        
        // Load semua data sekali saja (eager loading)
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $sizeStocks = SizeStockProduct::whereIn('id', $sizeStockIds)->get()->keyBy('id');
        
        // Map order items dengan data yang sudah di-load
        return collect($orderItems)->map(function($item) use ($products, $sizeStocks) {
            $productId = $item['product_id'];
            $sizeStockId = $item['size_stock_product_id'];
            
            return [
                'product' => $products->get($productId),
                'size_stock' => $sizeStocks->get($sizeStockId),
                'qty' => $item['qty']
            ];
        });
    }

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
