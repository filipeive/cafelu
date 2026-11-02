<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = \App\Models\Tenant::where('status', 'active')->first() ?? \App\Models\Tenant::first();

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'tenant_id' => $tenant?->id ?? 1,
                'name' => 'Administrador',
                'email' => 'admin@cafelu.test',
                'password' => 'admin1234',
                'role' => 'admin',
                'status' => 'active',
            ]
        );
    }
}
