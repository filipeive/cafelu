<?php

namespace Tests\Feature\Settings;

use App\Models\Setting;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsSaveTest extends TestCase
{
    public function test_settings_update_persists_and_clears_cache(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'cafe-lu-test'],
            [
                'name'              => 'Cafe Lu Original',
                'status'            => 'active',
                'installation_mode' => 'cloud',
                'plan'              => 'annual',
            ]
        );

        $admin = User::firstOrCreate(
            ['username' => 'test_admin_settings'],
            [
                'name'      => 'Admin Settings',
                'email'     => 'admin_settings@test.com',
                'password'  => bcrypt('password'),
                'role'      => 'admin',
                'tenant_id' => $tenant->id,
            ]
        );

        // 1. Test updateName
        $response = $this->actingAs($admin)->post(route('settings.update-name'), [
            'company_name' => 'Café Lu & Yoshi Atualizado',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('Café Lu & Yoshi Atualizado', Setting::get('company_name'));
        $this->assertEquals('Café Lu & Yoshi Atualizado', $tenant->fresh()->name);

        // 2. Test updateColors
        $responseColors = $this->actingAs($admin)->post(route('settings.update-colors'), [
            'primary_color'   => '#FF5722',
            'secondary_color' => '#2196F3',
        ]);

        $responseColors->assertSessionHas('success');
        $this->assertEquals('#FF5722', Setting::get('primary_color'));
        $this->assertEquals('#2196F3', Setting::get('secondary_color'));

        // 3. Test main settings update
        $responseMain = $this->actingAs($admin)->post(route('settings.update'), [
            'company_address' => 'Avenida Principal 123, Quelimane',
            'company_phone'   => '+258 84 999 1111',
            'tax_rate'        => '16',
        ]);

        $responseMain->assertRedirect(route('settings.index'));
        $this->assertEquals('Avenida Principal 123, Quelimane', Setting::get('company_address'));
        $this->assertEquals('+258 84 999 1111', Setting::get('company_phone'));
        $this->assertEquals('+258 84 999 1111', $tenant->fresh()->phone);
    }
}

