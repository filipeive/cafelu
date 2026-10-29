<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Sale;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobilePwaAndDualViewTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::firstOrCreate(
            ['slug' => 'lu-yoshi'],
            [
                'name' => 'Café Lu & Yoshi',
                'subdomain' => 'lu-yoshi',
                'is_active' => true,
            ]
        );

        $this->admin = User::firstOrCreate(
            ['email' => 'admin.test@restpos.co.mz'],
            [
                'name' => 'Admin Teste',
                'username' => 'admintest',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'tenant_id' => $this->tenant->id,
            ]
        );
    }

    public function test_pwa_manifest_file_is_valid_and_has_required_schema()
    {
        $manifestPath = public_path('manifest.json');
        $this->assertFileExists($manifestPath);

        $content = json_decode(file_get_contents($manifestPath), true);
        $this->assertIsArray($content);
        $this->assertEquals('RestPOS - Gestão de Restaurantes & POS', $content['name']);
        $this->assertEquals('RestPOS', $content['short_name']);
        $this->assertEquals('standalone', $content['display']);
        $this->assertEquals('#f97316', $content['theme_color']);
        $this->assertNotEmpty($content['icons']);
        $this->assertNotEmpty($content['shortcuts']);
    }

    public function test_service_worker_script_exists_and_contains_pwa_caching_logic()
    {
        $swPath = public_path('sw.js');
        $this->assertFileExists($swPath);

        $swContent = file_get_contents($swPath);
        $this->assertStringContainsString('restpos-pwa', $swContent);
        $this->assertStringContainsString('skipWaiting()', $swContent);
        $this->assertStringContainsString('clients.claim()', $swContent);
        $this->assertStringContainsString('/process-sale', $swContent);
    }

    public function test_app_layout_renders_pwa_meta_tags_bottom_navigation_and_install_banner()
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('manifest.json');
        $response->assertSee('apple-touch-icon.png');
        $response->assertSee('viewport-fit=cover');
        // Mobile Bottom Navigation
        $response->assertSee('Mobile PWA Bottom Navigation Bar', false);
        $response->assertSee('Frente de Caixa POS', false);
        $response->assertSee('Cozinha', false);
        $response->assertSee('Mesas', false);
        // Install banner
        $response->assertSee('Instalar App RestPOS', false);
    }

    public function test_sales_and_orders_index_render_dual_view_for_desktop_and_mobile()
    {
        // Sales View
        $responseSales = $this->actingAs($this->admin)->get(route('sales.index'));
        $responseSales->assertStatus(200);
        $responseSales->assertSee('Table for Desktop', false);
        $responseSales->assertSee('Mobile / PWA Card View', false);

        // Orders View
        $responseOrders = $this->actingAs($this->admin)->get(route('orders.index'));
        $responseOrders->assertStatus(200);
        $responseOrders->assertSee('Table for Desktop', false);
        $responseOrders->assertSee('Mobile / PWA Card View', false);
    }

    public function test_welcome_and_network_pages_render_apk_download_buttons_and_links()
    {
        // 1. Restaurant Welcome Menu Page (clean link to dedicated page, no clutter)
        $responseRestaurant = $this->get(route('restaurant.menu', ['tenant' => $this->tenant->slug]));
        $responseRestaurant->assertStatus(200);
        $responseRestaurant->assertSee('App Móvel', false);
        $responseRestaurant->assertSee('Baixe a Nossa App', false);

        // 2. Network Welcome Page (clean link to dedicated page)
        $responseNetwork = $this->get(route('welcome'));
        $responseNetwork->assertStatus(200);
        $responseNetwork->assertSee('Baixe a Nossa App', false);

        // 3. Dedicated App Download Page (General)
        $responseAppGeneral = $this->get(route('app.download'));
        $responseAppGeneral->assertStatus(200);
        $responseAppGeneral->assertSee('Baixe a Nossa App Móvel', false);
        $responseAppGeneral->assertSee('downloads/RestPOS-v1.3.apk', false);
        $responseAppGeneral->assertSee('O que você pode fazer com a nossa aplicação?', false);
        $responseAppGeneral->assertSee('Como instalar no seu celular em 3 passos', false);

        // 4. Dedicated App Download Page (Tenant-branded)
        $responseAppTenant = $this->get(route('restaurant.app', ['tenant' => $this->tenant->slug]));
        $responseAppTenant->assertStatus(200);
        $responseAppTenant->assertSee(e($this->tenant->name), false);
        $responseAppTenant->assertSee('downloads/RestPOS-v1.3.apk', false);
        $responseAppTenant->assertSee('Voltar ao Restaurante', false);
    }
}
