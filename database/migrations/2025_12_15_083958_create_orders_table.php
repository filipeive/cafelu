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
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('table_id')->nullable()->index('table_id');
            $table->integer('user_id');
            $table->string('customer_name')->nullable();
            $table->enum('status', ['active', 'completed', 'paid', 'canceled'])->nullable()->default('active');
            $table->decimal('total_amount', 10)->nullable()->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->enum('payment_method', ['cash', 'card', 'mpesa', 'emola', 'mkesh'])->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
