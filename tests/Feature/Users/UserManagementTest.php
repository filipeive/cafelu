<?php

namespace Tests\Feature\Users;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_admin_can_create_user_without_explicit_status(): void
    {
        $tenant = Tenant::create([
            'name' => 'Café Lu & Yoshi',
            'slug' => 'cafe-lu-yoshi',
            'status' => 'active',
        ]);

        $admin = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin Cafe Lu',
            'username' => 'admin_cafelu',
            'email' => 'admin@cafelu.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Novo Garçom',
            'email' => 'garcom@cafelu.com',
            'username' => 'garcom1',
            'password' => '1234',
            'role' => 'waiter',
            // status omitted to simulate modal form
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'username' => 'garcom1',
            'email' => 'garcom@cafelu.com',
            'role' => 'waiter',
            'status' => 'active',
            'tenant_id' => $tenant->id,
        ]);
    }

    public function test_tenant_admin_can_update_user(): void
    {
        $tenant = Tenant::create([
            'name' => 'Café Lu & Yoshi',
            'slug' => 'cafe-lu-yoshi',
            'status' => 'active',
        ]);

        $admin = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin Cafe Lu',
            'username' => 'admin_cafelu2',
            'email' => 'admin2@cafelu.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Garçom Antigo',
            'username' => 'garcom_antigo',
            'email' => 'garcom_antigo@cafelu.com',
            'password' => Hash::make('1234'),
            'role' => 'waiter',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->put(route('users.update', $user->id), [
            'name' => 'Garçom Atualizado',
            'email' => 'garcom_atualizado@cafelu.com',
            'username' => 'garcom_novo',
            'role' => 'manager',
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Garçom Atualizado',
            'username' => 'garcom_novo',
            'email' => 'garcom_atualizado@cafelu.com',
            'role' => 'manager',
            'status' => 'inactive',
        ]);
    }

    public function test_users_index_scopes_to_tenant(): void
    {
        $tenant1 = Tenant::create(['name' => 'Café Lu', 'slug' => 'cafe-lu', 'status' => 'active']);
        $tenant2 = Tenant::create(['name' => 'Zambézia Grill', 'slug' => 'zambezia-grill', 'status' => 'active']);

        $admin1 = User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Admin Lu',
            'username' => 'admin_lu',
            'email' => 'admin@lu.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $userTenant2 = User::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Funcionario Zambézia',
            'username' => 'func_zambezia',
            'email' => 'func@zambezia.com',
            'password' => Hash::make('password'),
            'role' => 'waiter',
        ]);

        $response = $this->actingAs($admin1)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee('Admin Lu');
        $response->assertDontSee('Funcionario Zambézia');
    }
}
