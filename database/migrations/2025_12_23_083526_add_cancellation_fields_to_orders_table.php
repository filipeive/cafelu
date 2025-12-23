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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('cancel_requested_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->enum('cancellation_status', ['none', 'pending', 'approved', 'rejected'])->default('none');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cancel_requested_at', 'cancellation_reason', 'cancellation_status']);
        });
    }
};
