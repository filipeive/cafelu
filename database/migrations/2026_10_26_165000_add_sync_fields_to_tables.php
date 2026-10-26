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
        // 1. Tenants: sync tracking
        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                if (!Schema::hasColumn('tenants', 'last_synced_at')) {
                    $table->timestamp('last_synced_at')->nullable()->index()->after('license_expires_at');
                }
                if (!Schema::hasColumn('tenants', 'sync_token')) {
                    $table->string('sync_token', 100)->nullable()->index()->after('last_synced_at');
                }
            });
        }

        // 2. Sales: offline_id and synced_at
        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                if (!Schema::hasColumn('sales', 'offline_id')) {
                    $table->string('offline_id', 100)->nullable()->index()->after('id');
                }
                if (!Schema::hasColumn('sales', 'synced_at')) {
                    $table->timestamp('synced_at')->nullable()->index()->after('updated_at');
                }
            });
        }

        // 3. Orders: offline_id and synced_at
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'offline_id')) {
                    $table->string('offline_id', 100)->nullable()->index()->after('id');
                }
                if (!Schema::hasColumn('orders', 'synced_at')) {
                    $table->timestamp('synced_at')->nullable()->index()->after('updated_at');
                }
            });
        }

        // 4. Stock Movements: offline_id, synced_at and tenant_id safety
        if (Schema::hasTable('stock_movements')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                if (!Schema::hasColumn('stock_movements', 'offline_id')) {
                    $table->string('offline_id', 100)->nullable()->index()->after('id');
                }
                if (!Schema::hasColumn('stock_movements', 'synced_at')) {
                    $table->timestamp('synced_at')->nullable()->index()->after('updated_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                if (Schema::hasColumn('tenants', 'last_synced_at')) $table->dropColumn('last_synced_at');
                if (Schema::hasColumn('tenants', 'sync_token')) $table->dropColumn('sync_token');
            });
        }

        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                if (Schema::hasColumn('sales', 'offline_id')) $table->dropColumn('offline_id');
                if (Schema::hasColumn('sales', 'synced_at')) $table->dropColumn('synced_at');
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'offline_id')) $table->dropColumn('offline_id');
                if (Schema::hasColumn('orders', 'synced_at')) $table->dropColumn('synced_at');
            });
        }

        if (Schema::hasTable('stock_movements')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                if (Schema::hasColumn('stock_movements', 'offline_id')) $table->dropColumn('offline_id');
                if (Schema::hasColumn('stock_movements', 'synced_at')) $table->dropColumn('synced_at');
            });
        }
    }
};

