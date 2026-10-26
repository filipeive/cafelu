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
        if (!Schema::hasTable('license_keys')) {
            Schema::create('license_keys', function (Blueprint $table) {
                $table->increments('id');
                $table->string('key_code', 64)->unique();
                $table->string('key_hash', 64)->nullable();
                $table->string('business_name')->nullable();
                $table->string('customer_name')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_phone')->nullable();
                $table->string('license_type', 30)->default('annual'); // trial, monthly, quarterly, annual, lifetime
                $table->string('status', 20)->default('active'); // active, expired, suspended, revoked
                $table->dateTime('starts_at');
                $table->dateTime('expires_at')->nullable();
                $table->dateTime('activated_at')->nullable();
                $table->integer('max_tables')->default(50);
                $table->integer('max_users')->default(10);
                $table->string('device_fingerprint')->nullable();
                $table->boolean('is_current')->default(false);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_keys');
    }
};
