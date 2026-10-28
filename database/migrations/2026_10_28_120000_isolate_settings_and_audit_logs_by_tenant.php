<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add tenant_id to settings and replace global unique(key) with unique(tenant_id, key)
        if (Schema::hasTable('settings')) {
            if (!Schema::hasColumn('settings', 'tenant_id')) {
                Schema::table('settings', function (Blueprint $table) {
                    $table->unsignedBigInteger('tenant_id')->nullable()->after('id')->index();
                });

                // Assign existing settings to initial tenant #1
                DB::table('settings')->whereNull('tenant_id')->update(['tenant_id' => 1]);
            }

            try {
                Schema::table('settings', function (Blueprint $table) {
                    $table->dropUnique('settings_key_unique');
                });
            } catch (\Throwable $e) {
                // Index may already be dropped or named differently in SQLite
            }

            try {
                Schema::table('settings', function (Blueprint $table) {
                    $table->unique(['tenant_id', 'key'], 'settings_tenant_key_unique');
                });
            } catch (\Throwable $e) {
                // Composite unique index already exists
            }
        }

        // 2. Add tenant_id to audit_logs so tenant admins only see their own restaurant's logs
        if (Schema::hasTable('audit_logs') && !Schema::hasColumn('audit_logs', 'tenant_id')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id')->index();
            });

            // Backfill tenant_id from users table for existing logs
            $usersWithTenant = DB::table('users')
                ->whereNotNull('tenant_id')
                ->pluck('tenant_id', 'id');

            foreach ($usersWithTenant as $userId => $tenantId) {
                DB::table('audit_logs')
                    ->where('user_id', $userId)
                    ->whereNull('tenant_id')
                    ->update(['tenant_id' => $tenantId]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('audit_logs') && Schema::hasColumn('audit_logs', 'tenant_id')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
        }

        if (Schema::hasTable('settings') && Schema::hasColumn('settings', 'tenant_id')) {
            try {
                Schema::table('settings', function (Blueprint $table) {
                    $table->dropUnique('settings_tenant_key_unique');
                });
            } catch (\Throwable $e) {
            }

            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
        }
    }
};
