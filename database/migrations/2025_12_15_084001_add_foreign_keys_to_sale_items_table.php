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
        Schema::table('sale_items', function (Blueprint $table) {
            $table->foreign(['sale_id'], 'sale_items_ibfk_1')->references(['id'])->on('sales')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['product_id'], 'sale_items_ibfk_2')->references(['id'])->on('products')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropForeign('sale_items_ibfk_1');
            $table->dropForeign('sale_items_ibfk_2');
        });
    }
};
