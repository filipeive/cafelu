<?php

namespace Tests\Feature\Sync;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Tenant;
use App\Models\LicenseKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalCloudSyncTest extends TestCase
{
    public function test_api_sync_status_returns_online(): void
    {
        $response = $this->getJson(route('api.sync.status'));
        $response->assertStatus(200)
                 ->assertJson([
                     'status'  => 'online',
                     'version' => 'v1.3',
                 ]);
    }

    public function test_verify_license_endpoint(): void
    {
        $tenant = Tenant::create([
            'name'              => 'Restaurante Teste Sync',
            'slug'              => 'teste-sync-' . uniqid(),
            'status'            => 'active',
            'installation_mode' => 'local_online',
            'plan'              => 'annual',
            'license_expires_at'=> now()->addYear(),
        ]);

        $license = LicenseKey::create([
            'tenant_id'    => $tenant->id,
            'key_code'     => 'CAFE-TEST-SYNC-KEY1-9999',
            'status'       => 'active',
            'license_type' => 'annual',
            'starts_at'    => now(),
            'expires_at'   => now()->addYear(),
        ]);

        $response = $this->postJson(route('api.sync.verify'), [
            'license_key' => 'CAFE-TEST-SYNC-KEY1-9999',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'valid' => true,
                     'tenant' => [
                         'slug' => $tenant->slug,
                     ],
                 ]);
    }

    public function test_ingest_sales_and_products_idempotently(): void
    {
        $tenant = Tenant::create([
            'name'              => 'Bistro Nuvem',
            'slug'              => 'bistro-nuvem-' . uniqid(),
            'status'            => 'active',
            'installation_mode' => 'local_online',
            'plan'              => 'annual',
        ]);

        $license = LicenseKey::create([
            'tenant_id'    => $tenant->id,
            'key_code'     => 'CAFE-BIST-SYNC-TOKEN-1234',
            'status'       => 'active',
            'license_type' => 'annual',
            'starts_at'    => now(),
            'expires_at'   => now()->addYear(),
        ]);

        $offlineSaleId = 'OFF-SALE-TEST-' . uniqid();

        $payload = [
            'tenant_slug' => $tenant->slug,
            'categories'  => [
                ['name' => 'Bebidas Tropicais', 'description' => 'Sucos naturais'],
            ],
            'products'    => [
                [
                    'name'          => 'Suco de Manga 500ml',
                    'category_name' => 'Bebidas Tropicais',
                    'price'         => 120.00,
                    'stock'         => 40,
                ],
            ],
            'sales'       => [
                [
                    'offline_id'     => $offlineSaleId,
                    'customer_name'  => 'Cliente Balcao',
                    'total_amount'   => 120.00,
                    'payment_method' => 'cash',
                    'cash_amount'    => 200.00,
                    'items'          => [
                        [
                            'product_name' => 'Suco de Manga 500ml',
                            'quantity'     => 1,
                            'unit_price'   => 120.00,
                            'subtotal'     => 120.00,
                        ],
                    ],
                ],
            ],
        ];

        // 1º Envio
        $response1 = $this->withHeaders([
            'X-Sync-Token' => 'CAFE-BIST-SYNC-TOKEN-1234',
        ])->postJson(route('api.sync.ingest'), $payload);

        $response1->assertStatus(200)
                  ->assertJson([
                      'success'       => true,
                      'sales_created' => 1,
                  ]);

        $this->assertDatabaseHas('sales', [
            'tenant_id'  => $tenant->id,
            'offline_id' => $offlineSaleId,
        ]);

        // 2º Envio (Idempotência — não pode duplicar nem dar erro)
        $response2 = $this->withHeaders([
            'X-Sync-Token' => 'CAFE-BIST-SYNC-TOKEN-1234',
        ])->postJson(route('api.sync.ingest'), $payload);

        $response2->assertStatus(200)
                  ->assertJson([
                      'success'       => true,
                      'sales_created' => 0,
                      'sales_skipped' => 1,
                  ]);

        $salesCount = Sale::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('offline_id', $offlineSaleId)
            ->count();

        $this->assertEquals(1, $salesCount);
    }
}
