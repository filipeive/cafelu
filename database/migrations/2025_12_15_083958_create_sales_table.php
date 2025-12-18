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
        Schema::create('sales', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('sale_date')->nullable()->useCurrent();
            $table->decimal('total_amount', 10);
            $table->string('payment_method', 50);
            $table->string('status', 20)->nullable()->default('completed');
            $table->decimal('cash_amount', 10)->nullable()->default(0);
            $table->decimal('card_amount', 10)->nullable()->default(0);
            $table->decimal('mpesa_amount', 10)->nullable()->default(0);
            $table->decimal('emola_amount', 10)->nullable()->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
