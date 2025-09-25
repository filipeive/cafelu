<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('receipt_file')->nullable()->after('receipt_number');
            $table->string('receipt_original_name')->nullable()->after('receipt_file');
            $table->string('receipt_mime_type')->nullable()->after('receipt_original_name');
            $table->unsignedBigInteger('receipt_file_size')->nullable()->after('receipt_mime_type');
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn([
                'receipt_file',
                'receipt_original_name',
                'receipt_mime_type',
                'receipt_file_size'
            ]);
        });
    }
};