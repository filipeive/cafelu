<?php

namespace Tests\Feature\Website;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WebsiteSlide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WebsiteCmsTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::first() ?? Tenant::create([
            'name' => 'Café Lu',
            'slug' => 'cafe-lu',
            'status' => 'active',
        ]);

        $this->admin = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Admin Lu',
            'username' => 'adminlu',
            'email' => 'admin@cafelu.co.mz',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function test_welcome_page_renders_hero_slider_and_menu_views(): void
    {
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Grelhados & Mariscos',
        ]);

        Product::create([
            'tenant_id' => $this->tenant->id,
            'category_id' => $category->id,
            'name' => 'Camarão Grelhado à Café Lu',
            'price' => 850,
            'selling_price' => 850,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Camarão Grelhado à Café Lu', false);
        $response->assertSee('Galeria com Fotos', false);
        $response->assertSee('Menu Clássico', false);
        $response->assertSee('Sugestões & Destaques da Casa', false);
    }

    public function test_admin_can_manage_website_cms_content_and_slides(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->get(route('website.index'))
            ->assertStatus(200)
            ->assertSee('Página Web &amp; Pedidos Online', false);

        // Update CMS Content Settings including GPS Geofence coordinates
        $this->actingAs($this->admin)
            ->post(route('website.content'), [
                'site_hero_badge' => 'Especialidades Moçambicanas',
                'site_hero_title' => 'O Melhor Sabor da Cidade',
                'site_announcement' => 'Entregas grátis em compras acima de 2000 MT',
                'site_about_title' => 'Nossa Tradição Culinária',
                'site_about_text' => 'Desde 2018 servindo pratos frescos e café selecionado.',
                'site_opening_hours' => 'Seg - Dom: 06:30 às 23:00',
                'site_whatsapp' => '258841234567',
                'site_latitude' => '-25.969200',
                'site_longitude' => '32.573200',
                'site_geofence_radius' => '150',
                'site_online_orders' => '1',
            ])
            ->assertRedirect();

        // Upload a Hero Banner Slide
        $slideImage = UploadedFile::fake()->image('banner1.jpg', 1200, 600);

        $this->actingAs($this->admin)
            ->post(route('website.slides.store'), [
                'title' => 'Festival de Mariscos & Grelhados',
                'subtitle' => 'Venha provar as nossas especialidades.',
                'button_text' => 'Encomendar Agora',
                'button_link' => '#menu',
                'image' => $slideImage,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('website_slides', [
            'title' => 'Festival de Mariscos & Grelhados',
            'button_text' => 'Encomendar Agora',
        ]);

        // Verify the public welcome page reflects the CMS updates and slide
        $welcome = $this->get('/');
        $welcome->assertStatus(200);
        $welcome->assertSee('Entregas grátis em compras acima de 2000 MT');
        $welcome->assertSee('Nossa Tradição Culinária');
        $welcome->assertSee('Festival de Mariscos & Grelhados', false);
        $welcome->assertSee('Seg - Dom: 06:30 às 23:00');
        $welcome->assertSee('calculateDistanceMeters', false);
        $welcome->assertSee('-25.969200', false);
        $welcome->assertSee("serviceType: 'takeaway'", false);

        // When accessed via QR Code (?mesa=3), it automatically switches to dine_in (Presencial)
        $qrWelcome = $this->get('/?mesa=3');
        $qrWelcome->assertStatus(200);
        $qrWelcome->assertSee("serviceType: 'dine_in'", false);
    }

    public function test_pos_page_has_high_contrast_action_buttons(): void
    {
        $response = $this->actingAs($this->admin)->get(route('pos.index'));

        $response->assertStatus(200);
        $response->assertSee('bg-amber-500');
        $response->assertSee('bg-blue-600');
        $response->assertSee('bg-rose-600');
        $response->assertSee('bg-emerald-600');
    }

    public function test_call_waiter_dispatches_real_time_notification_with_sound_trigger_to_staff(): void
    {
        $welcome = $this->get('/');
        $welcome->assertStatus(200);
        $welcome->assertSee('Como deseja ser atendido hoje?', false);
        $welcome->assertSee('Estou no Restaurante', false);
        $welcome->assertSee('Reservar uma Mesa Online', false);

        $callResponse = $this->postJson(route('welcome.call-waiter'), [
            'table' => 'Mesa 5',
            'name' => 'Filipe',
            'reason' => 'Pediu a conta na mesa',
        ]);

        $callResponse->assertStatus(200)
            ->assertJson(['success' => true]);

        $unreadResponse = $this->actingAs($this->admin)
            ->getJson(route('notifications.unread'));

        $unreadResponse->assertStatus(200)
            ->assertJsonPath('notifications.0.type', 'waiter_called')
            ->assertJsonPath('notifications.0.table_name', 'Mesa 5');
    }
}
