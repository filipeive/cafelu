<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'), // Change this!
            'name' => 'Administrator',
            'role' => 'admin',
        ]);
        
        // Create manager user
        User::create([
            'username' => 'manager',
            'password' => Hash::make('password'), // Change this!
            'name' => 'Restaurant Manager',
            'role' => 'manager',
        ]);
        
        // Create waiter user
        User::create([
            'username' => 'waiter',
            'password' => Hash::make('password'), // Change this!
            'name' => 'Restaurant Waiter',
            'role' => 'waiter',
        ]);
    }
}

// Then update DatabaseSeeder.php to call this seeder
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            // Add other seeders here...
        ]);
    }
}