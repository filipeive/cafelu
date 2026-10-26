<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductIndexViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_page_renders_grid_and_list_views_and_actions(): void
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
            'name' => 'Bebidas Quentes',
            'is_active' => true,
        ]);

        $product = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $category->id,
            'name' => 'Café Pingado',
            'price' => 50.00,
            'selling_price' => 50.00,
            'purchase_price' => 20.00,
            'stock_quantity' => 25,
            'type' => 'product',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Café Pingado');
        $response->assertSee('viewMode === \'grid\'', false);
        $response->assertSee('viewMode === \'list\'', false);
        $response->assertSee('editProduct(' . $product->id . ')', false);
        $response->assertSee('updateStock(' . $product->id, false);
        $response->assertSee('deleteProduct(' . $product->id, false);
    }
}
