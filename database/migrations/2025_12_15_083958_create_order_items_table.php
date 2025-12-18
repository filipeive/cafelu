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
        Schema::create('order_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_id')->nullable()->index('order_id');
            $table->integer('product_id')->nullable()->index('order_items_ibfk_2');
            $table->integer('quantity');
            $table->decimal('unit_price', 10);
            $table->decimal('total_price', 10);
            $table->enum('status', ['pending', 'preparing', 'ready', 'delivered', 'cancelled'])->nullable()->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
