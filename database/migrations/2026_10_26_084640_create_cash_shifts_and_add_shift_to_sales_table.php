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
        if (!Schema::hasTable('cash_shifts')) {
            Schema::create('cash_shifts', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->index('user_id');
                $table->dateTime('opened_at');
                $table->dateTime('closed_at')->nullable();
                $table->decimal('opening_balance', 12, 2)->default(0);
                $table->decimal('closing_balance_system', 12, 2)->nullable();
                $table->decimal('closing_balance_actual', 12, 2)->nullable();
                $table->decimal('difference', 12, 2)->default(0);
                $table->decimal('cash_sales', 12, 2)->default(0);
                $table->decimal('card_sales', 12, 2)->default(0);
                $table->decimal('mpesa_sales', 12, 2)->default(0);
                $table->decimal('emola_sales', 12, 2)->default(0);
                $table->decimal('total_sales', 12, 2)->default(0);
                $table->string('status', 20)->default('open'); // open, closed
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('sales') && !Schema::hasColumn('sales', 'cash_shift_id')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->integer('cash_shift_id')->nullable()->after('user_id');
                $table->index('cash_shift_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sales') && Schema::hasColumn('sales', 'cash_shift_id')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->dropIndex(['cash_shift_id']);
                $table->dropColumn('cash_shift_id');
            });
        }

        Schema::dropIfExists('cash_shifts');
    }
};
