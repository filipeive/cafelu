<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class ProductEditAndReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_translations_for_edit_and_save_exist(): void
    {
        $this->assertTrue(Lang::has('messages.edit', 'pt'));
        $this->assertEquals('Editar', __('messages.edit', [], 'pt'));
        $this->assertEquals('Edit', __('messages.edit', [], 'en'));

        $this->assertTrue(Lang::has('messages.save', 'pt'));
        $this->assertEquals('Salvar', __('messages.save', [], 'pt'));
        $this->assertEquals('Save', __('messages.save', [], 'en'));
    }

    public function test_product_show_loads_previous_price(): void
    {
        $tenant = Tenant::create([
            'name' => 'Café Lu & Yoshi',
            'slug' => 'cafe-lu-yoshi',
            'status' => 'active',
        ]);

        $admin = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $category = Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'Bebidas',
            'is_active' => true,
        ]);

        // Legacy product with price set but selling_price null
        $product = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $category->id,
            'name' => 'Café Expresso',
            'price' => 75.00,
            'selling_price' => null,
            'purchase_price' => null,
            'stock_quantity' => 10,
            'type' => 'product',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->getJson("/products/{$product->id}");

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals(75.00, $data['selling_price']);
        $this->assertEquals('0.00', $data['purchase_price']);
    }

    public function test_receipt_renders_logo_and_establishment_name(): void
    {
        $tenant = Tenant::create([
            'name' => 'Café Lu & Yoshi',
            'slug' => 'cafe-lu-yoshi',
            'phone' => '+258 84 123 4567',
            'address' => 'Av. Samora Machel',
            'city' => 'Quelimane',
            'status' => 'active',
        ]);

        $admin = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $sale = Sale::create([
            'tenant_id' => $tenant->id,
            'user_id' => $admin->id,
            'subtotal' => 150.00,
            'total_amount' => 150.00,
            'paid_amount' => 150.00,
            'change_amount' => 0.00,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'status' => 'completed',
            'sale_date' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('sales.receipt', $sale->id));

        $response->assertStatus(200);
        $response->assertSee('Café Lu & Yoshi');
        $response->assertSee('class="logo"', false);
    }
}
