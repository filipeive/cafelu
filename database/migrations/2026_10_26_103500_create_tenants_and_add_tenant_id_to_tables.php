<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create tenants table
        if (!Schema::hasTable('tenants')) {
            Schema::create('tenants', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('domain')->nullable()->unique();
                $table->string('subdomain')->nullable()->index();
                $table->string('nuit', 30)->nullable()->index();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->default('Quelimane');
                $table->string('currency', 5)->default('MZN');
                $table->string('status')->default('active'); // active, trial, suspended, cancelled
                $table->string('installation_mode')->default('cloud'); // cloud, local_online, offline
                $table->string('plan')->default('annual'); // trial, monthly, quarterly, annual, lifetime
                $table->integer('max_tables')->default(50);
                $table->integer('max_users')->default(15);
                $table->timestamp('trial_ends_at')->nullable();
                $table->timestamp('license_expires_at')->nullable();
                $table->json('settings')->nullable();
                $table->timestamps();
            });
        }

        // 2. Add tenant_id safely to all business tables
        $tablesWithTenant = [
            'users',
            'categories',
            'products',
            'tables',
            'orders',
            'order_items',
            'sales',
            'sale_items',
            'expenses',
            'cash_shifts',
            'customer_debts',
            'debt_payments',
            'clients',
            'employees',
            'stock_movements',
            'notifications',
            'license_keys',
        ];

        foreach ($tablesWithTenant as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('tenant_id')->nullable()->index();
                });
            }
        }

        // 3. Seed default tenant for existing installation and link existing data
        $existingTenant = DB::table('tenants')->where('id', 1)->first();
        if (!$existingTenant) {
            DB::table('tenants')->insert([
                'id' => 1,
                'name' => 'Lu & Yoshi Cafe',
                'slug' => 'lu-yoshi',
                'subdomain' => 'luyoshi',
                'nuit' => '110735901',
                'email' => 'contato@luyoshicafe.com',
                'phone' => '+258 84 000 0000',
                'address' => 'Zalala Beach, Quelimane',
                'city' => 'Quelimane',
                'currency' => 'MZN',
                'status' => 'active',
                'installation_mode' => 'cloud',
                'plan' => 'annual',
                'max_tables' => 50,
                'max_users' => 15,
                'license_expires_at' => now()->addYear(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign existing business data to tenant #1
        foreach ($tablesWithTenant as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                if ($tableName === 'users') {
                    // All users except superadmin belong to tenant 1
                    DB::table('users')
                        ->where('role', '!=', 'superadmin')
                        ->whereNull('tenant_id')
                        ->update(['tenant_id' => 1]);
                } else {
                    DB::table($tableName)
                        ->whereNull('tenant_id')
                        ->update(['tenant_id' => 1]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tablesWithTenant = [
            'users',
            'categories',
            'products',
            'tables',
            'orders',
            'order_items',
            'sales',
            'sale_items',
            'expenses',
            'cash_shifts',
            'customer_debts',
            'debt_payments',
            'clients',
            'employees',
            'stock_movements',
            'notifications',
            'license_keys',
        ];

        foreach ($tablesWithTenant as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('tenant_id');
                });
            }
        }

        Schema::dropIfExists('tenants');
    }
};

