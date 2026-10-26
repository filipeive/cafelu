<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Populate selling_price from price where selling_price is NULL or 0
        DB::statement("UPDATE products SET selling_price = price WHERE (selling_price IS NULL OR selling_price = 0) AND price IS NOT NULL AND price > 0");

        // Ensure purchase_price is at least 0.00 if NULL
        DB::statement("UPDATE products SET purchase_price = 0.00 WHERE purchase_price IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed
    }
};
