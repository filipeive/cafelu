<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            ClientsTableSeeder::class,
            EmployeesTableSeeder::class,
            MenusTableSeeder::class,
            ProductsTableSeeder::class,
            TablesTableSeeder::class,
            OrdersTableSeeder::class,
            OrderItemsTableSeeder::class,
            SalesTableSeeder::class,
            SaleItemsTableSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
