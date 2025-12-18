<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum column using raw SQL because Doctrine DBAL has issues with enums
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('active', 'completed', 'paid', 'canceled', 'held') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('active', 'completed', 'paid', 'canceled') DEFAULT 'active'");
    }
};
