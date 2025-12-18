<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('role');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'created_at')) {
                $table->timestamps();
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'purchase_price')) {
                $table->decimal('purchase_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'selling_price')) {
                $table->decimal('selling_price', 10, 2)->nullable()->after('purchase_price');
            }
            if (!Schema::hasColumn('products', 'type')) {
                $table->string('type')->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('products', 'unit')) {
                $table->string('unit')->nullable()->after('type');
            }
            if (!Schema::hasColumn('products', 'min_stock_level')) {
                $table->integer('min_stock_level')->default(0)->after('stock_quantity');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('total_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'last_login_at', 'created_at', 'updated_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['purchase_price', 'selling_price', 'type', 'unit', 'min_stock_level']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status']);
        });
    }
};
