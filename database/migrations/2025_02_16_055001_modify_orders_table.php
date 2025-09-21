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
            $table->string('addres')->nullable()->change();
            $table->enum('status', ['cancel', 'pending', 'pesanan dibuat', 'pesanan diantar', 'pesanan sampai', 'menunggu confirm user', 'completed'])->default('pending')->change();
            $table->string('payment_proof')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('addres')->nullable(false)->change();
            $table->enum('status', ['pending', 'pesanan dibuat', 'pesanan diantar', 'pesanan sampai', 'menunggu confirm user', 'completed'])->default('pending')->change();
            $table->string('payment_proof')->nullable(false)->change();
        });
    }
};
