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
            $table->dropForeign(['city_id']);
            $table->unsignedBigInteger('city_id')->nullable()->change();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->unsignedBigInteger('city_id')->nullable(false)->change();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->change();
        });
    }
};
