<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('quotations')) {
            Schema::create('quotations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id')->nullable()->index();
                $table->unsignedBigInteger('client_id')->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('quotation_number', 50)->index();
                $table->string('customer_name');
                $table->string('customer_nuit', 30)->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_phone', 40)->nullable();
                $table->string('customer_address')->nullable();
                $table->date('date');
                $table->date('valid_until')->nullable();
                $table->decimal('subtotal', 14, 2)->default(0);
                $table->decimal('discount_amount', 14, 2)->default(0);
                $table->decimal('tax_rate', 5, 2)->default(16.00);
                $table->decimal('tax_amount', 14, 2)->default(0);
                $table->decimal('total_amount', 14, 2)->default(0);
                $table->string('tax_regime', 30)->default('normal'); // normal, exempt, simplified
                $table->string('tax_exemption_reason')->nullable();
                $table->boolean('prices_include_tax')->default(true);
                $table->string('status', 20)->default('draft')->index(); // draft, sent, approved, rejected, converted
                $table->unsignedBigInteger('converted_sale_id')->nullable()->index();
                $table->timestamp('converted_at')->nullable();
                $table->text('notes')->nullable();
                $table->text('terms_conditions')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('quotation_items')) {
            Schema::create('quotation_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id')->nullable()->index();
                $table->foreignId('quotation_id')->constrained('quotations')->cascadeOnDelete();
                $table->unsignedBigInteger('product_id')->nullable()->index();
                $table->string('item_name');
                $table->text('description')->nullable();
                $table->decimal('quantity', 10, 2)->default(1);
                $table->decimal('unit_price', 12, 2)->default(0);
                $table->decimal('discount_amount', 12, 2)->default(0);
                $table->decimal('tax_rate', 5, 2)->default(16.00);
                $table->decimal('tax_amount', 12, 2)->default(0);
                $table->decimal('total_price', 14, 2)->default(0);
                $table->boolean('is_tax_exempt')->default(false);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                if (!Schema::hasColumn('sales', 'invoice_type')) {
                    $table->string('invoice_type', 30)->default('receipt')->after('payment_method');
                }
                if (!Schema::hasColumn('sales', 'invoice_number')) {
                    $table->string('invoice_number', 50)->nullable()->index()->after('invoice_type');
                }
                if (!Schema::hasColumn('sales', 'subtotal')) {
                    $table->decimal('subtotal', 14, 2)->default(0)->after('customer_name');
                }
                if (!Schema::hasColumn('sales', 'discount_amount')) {
                    $table->decimal('discount_amount', 14, 2)->default(0)->after('subtotal');
                }
                if (!Schema::hasColumn('sales', 'tax_regime')) {
                    $table->string('tax_regime', 30)->default('normal')->after('total_amount');
                }
                if (!Schema::hasColumn('sales', 'tax_rate')) {
                    $table->decimal('tax_rate', 5, 2)->default(16.00)->after('tax_regime');
                }
                if (!Schema::hasColumn('sales', 'tax_amount')) {
                    $table->decimal('tax_amount', 14, 2)->default(0.00)->after('tax_rate');
                }
                if (!Schema::hasColumn('sales', 'prices_include_tax')) {
                    $table->boolean('prices_include_tax')->default(true)->after('tax_amount');
                }
                if (!Schema::hasColumn('sales', 'customer_nuit')) {
                    $table->string('customer_nuit', 30)->nullable()->after('customer_name');
                }
                if (!Schema::hasColumn('sales', 'customer_phone')) {
                    $table->string('customer_phone', 40)->nullable()->after('customer_nuit');
                }
                if (!Schema::hasColumn('sales', 'customer_address')) {
                    $table->string('customer_address')->nullable()->after('customer_phone');
                }
                if (!Schema::hasColumn('sales', 'due_date')) {
                    $table->date('due_date')->nullable()->after('sale_date');
                }
                if (!Schema::hasColumn('sales', 'quotation_id')) {
                    $table->unsignedBigInteger('quotation_id')->nullable()->index()->after('order_id');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};
