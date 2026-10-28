<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Sale;
use App\Models\Table;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrTablesQuotationsAndTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $superAdmin;
    protected User $tenantAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::first() ?? Tenant::create([
            'name' => 'Lu & Yoshi Cafe',
            'slug' => 'lu-yoshi',
            'nuit' => '400999888',
            'status' => 'active',
        ]);

        $this->superAdmin = User::create([
            'tenant_id' => null,
            'name' => 'Platform Owner',
            'username' => 'superowner',
            'email' => 'owner@restpos.co.mz',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'status' => 'active',
        ]);

        $this->tenantAdmin = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Gerente Lu & Yoshi',
            'username' => 'gerentelu',
            'email' => 'gerente@luyoshi.co.mz',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function test_superadmin_is_strictly_isolated_from_tenant_operational_data_outside_support_mode(): void
    {
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Cafetaria',
        ]);

        Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Cappuccino Lu & Yoshi',
            'price' => 250,
            'selling_price' => 250,
            'stock_quantity' => 50,
            'is_active' => true,
        ]);

        // 1. In Normal Mode (Owner Console), SuperAdmin redirected from operational routes to /admin/tenants
        $this->actingAs($this->superAdmin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.tenants.index'));

        $this->actingAs($this->superAdmin)
            ->get(route('products.index'))
            ->assertRedirect(route('admin.tenants.index'));

        // TenantScope returns 0 tenant products when SuperAdmin is in Normal Mode
        $this->actingAs($this->superAdmin);
        app(\App\Services\TenantContext::class)->setTenant(null);
        $this->assertCount(0, Product::all());

        // 2. SuperAdmin can open and download the license certificate for Tenant #1 even if no LicenseKey existed initially
        $this->actingAs($this->superAdmin)
            ->get(route('admin.tenants.certificate', $this->tenant))
            ->assertStatus(200)
            ->assertSee('Certificado Oficial de Licenciamento de Software')
            ->assertSee('CAFE-');

        $this->actingAs($this->superAdmin)
            ->get(route('admin.tenants.certificate-pdf', $this->tenant))
            ->assertStatus(200);

        // 3. When SuperAdmin enters Support Mode for Lu & Yoshi, they can access operational routes and products
        $this->actingAs($this->superAdmin)
            ->withSession([
                'impersonated_tenant_id' => $this->tenant->id,
                'is_support_mode' => true,
            ])
            ->get(route('products.index'))
            ->assertStatus(200)
            ->assertSee('Cappuccino Lu & Yoshi');
    }

    public function test_table_creation_qr_code_flow_and_deleted_table_graceful_fallback(): void
    {
        // Create a new table as tenant admin
        $response = $this->actingAs($this->tenantAdmin)
            ->post(route('tables.store'), [
                'number' => 12,
                'capacity' => 6,
            ]);

        $response->assertRedirect(route('tables.index'));
        $response->assertSessionHas('new_table_qr_id');

        $table = Table::where('number', 12)->firstOrFail();
        $this->assertStringContainsString('/m/12', $table->qr_code_url);

        // Printable QR Code sheet works
        $this->actingAs($this->tenantAdmin)
            ->get(route('tables.qrcodes.print'))
            ->assertStatus(200)
            ->assertSee('MESA 12')
            ->assertSee('/m/12');

        // Customer scans /m/12 -> sets table 12 in session and opens menu in dine_in mode
        $scanResponse = $this->get(route('welcome.table.short', ['table' => 12]));
        $scanResponse->assertRedirect(route('welcome', ['mesa' => 12]));
        $scanResponse->assertSessionHas('self_service_table_id', $table->id);
        $scanResponse->assertSessionHas('self_service_table_number', '12');

        $menuResponse = $this->withSession([
            'self_service_table_id' => $table->id,
            'self_service_table_number' => '12',
        ])->get(route('welcome', ['mesa' => 12]));

        $menuResponse->assertStatus(200);
        $menuResponse->assertSee("serviceType: 'dine_in'", false);

        // Admin deletes table 12
        $this->actingAs($this->tenantAdmin)
            ->delete(route('tables.destroy', $table->id))
            ->assertRedirect(route('tables.index'));

        // Customer scans physical QR code of deleted table 12 -> graceful fallback to general menu
        $deletedScan = $this->get(route('welcome.table.short', ['table' => 12]));
        $deletedScan->assertRedirect(route('welcome'));
        $deletedScan->assertSessionHas('qr_table_fallback');
    }

    public function test_quotations_lifecycle_pdf_and_conversion_to_official_invoice_and_receipt(): void
    {
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Pratos Principais',
        ]);

        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Bife da Vazia Grelhado',
            'price' => 1000,
            'selling_price' => 1000,
            'stock_quantity' => 20,
            'is_active' => true,
        ]);

        // 1. Create a Quotation
        $storeResponse = $this->actingAs($this->tenantAdmin)
            ->post(route('quotations.store'), [
                'customer_name' => 'Empresa MozTech, Lda',
                'customer_nuit' => '400112233',
                'customer_phone' => '841234567',
                'customer_email' => 'compras@moztech.co.mz',
                'customer_address' => 'Av. 24 de Julho, Maputo',
                'date' => now()->format('Y-m-d'),
                'valid_until' => now()->addDays(15)->format('Y-m-d'),
                'tax_regime' => 'normal',
                'tax_rate' => 16,
                'prices_include_tax' => '1',
                'items' => [
                    [
                        'product_id' => $product->id,
                        'item_name' => 'Bife da Vazia Grelhado (Catering)',
                        'description' => 'Inclui acompanhamento',
                        'quantity' => 5,
                        'unit_price' => 1000,
                        'discount' => 0,
                    ],
                ],
            ]);

        $quotation = Quotation::firstOrFail();
        $storeResponse->assertRedirect(route('quotations.show', $quotation));

        $this->assertStringStartsWith('COT-' . now()->format('Y') . '/', $quotation->quotation_number);
        $this->assertEquals(5000.00, (float) $quotation->total_amount);
        $this->assertGreaterThan(0, (float) $quotation->tax_amount);

        // 2. Download Quotation A4 PDF
        $pdfResponse = $this->actingAs($this->tenantAdmin)
            ->get(route('quotations.pdf', $quotation));
        $pdfResponse->assertStatus(200);

        // 3. Convert Quotation to Factura-Recibo (cash_invoice -> FR-YYYY/0001)
        $convertResponse = $this->actingAs($this->tenantAdmin)
            ->post(route('quotations.convert', $quotation), [
                'invoice_type' => 'cash_invoice',
                'payment_method' => 'mpesa',
            ]);

        $sale = Sale::firstOrFail();
        $convertResponse->assertRedirect(route('sales.show', $sale));

        $this->assertStringStartsWith('FR-' . now()->format('Y') . '/', $sale->invoice_number);
        $this->assertEquals('Factura-Recibo', $sale->official_invoice_title);
        $this->assertEquals('400112233', $sale->customer_nuit);
        $this->assertEquals(15, $product->fresh()->stock_quantity);
        $this->assertEquals('converted', $quotation->fresh()->status);

        // 4. Download Official Sale Invoice A4 PDF
        $salePdf = $this->actingAs($this->tenantAdmin)
            ->get(route('sales.invoice.pdf', $sale->id));
        $salePdf->assertStatus(200);

        // 5. Update Sale Document Type to Factura Comercial (FT)
        $this->actingAs($this->tenantAdmin)
            ->post(route('sales.update-invoice', $sale->id), [
                'invoice_type' => 'invoice',
                'customer_name' => 'Empresa MozTech Corporativo, SA',
                'customer_nuit' => '400555777',
                'tax_regime' => 'normal',
            ])
            ->assertRedirect();

        $sale->refresh();
        $this->assertStringStartsWith('FT-' . now()->format('Y') . '/', $sale->invoice_number);
        $this->assertEquals('Factura Comercial', $sale->official_invoice_title);
        $this->assertEquals('400555777', $sale->customer_nuit);
    }
}
