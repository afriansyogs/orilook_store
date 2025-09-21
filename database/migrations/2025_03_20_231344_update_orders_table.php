<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'pesanan_dibuat', 'completed', 'request cancel', 'canceled'])->default('pending')->change();
            
            $table->string('order_code')->unique()->after('voucher_id'); 
            $table->string('payment_token')->nullable()->after('payment_id');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->nullable()->after('payment_token'); 
            $table->enum('shipping_status', ['not_shipped', 'shipped', 'delivered', 'returned'])->default('not_shipped')->nullable()->after('status'); 
            $table->text('order_item')->nullable()->after('shipping_status'); 

        // Optional: Jika kolom lama seperti `payment_proof` sudah tidak digunakan
        $table->dropColumn(['payment_proof']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'payment_token', 'payment_status', 'shipping_status', 'products']);
            $table->string('payment_proof')->nullable();
        });
    }
};
