<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryPrepTime;

class CategoryPrepTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tempos estimados padrão por tipo de categoria (em minutos)
        $defaultTimes = [
            // Bebidas rápidas
            'Bebidas' => 3,
            'Sucos' => 5,
            'Refrigerantes' => 2,
            'Cafés' => 4,
            
            // Entradas e petiscos
            'Entradas' => 10,
            'Petiscos' => 12,
            'Saladas' => 8,
            
            // Pratos principais
            'Pratos Principais' => 25,
            'Carnes' => 30,
            'Massas' => 20,
            'Peixes' => 25,
            'Grelhados' => 28,
            
            // Lanches
            'Lanches' => 15,
            'Sanduíches' => 12,
            'Pizzas' => 20,
            
            // Sobremesas
            'Sobremesas' => 10,
            'Doces' => 8,
            
            // Sopas
            'Sopas' => 15,
        ];

        // Buscar todas as categorias ativas
        $categories = Category::where('is_active', true)->get();

        foreach ($categories as $category) {
            // Verificar se já existe um tempo estimado
            $existingPrepTime = CategoryPrepTime::where('category_id', $category->id)->first();
            
            if (!$existingPrepTime) {
                // Buscar tempo padrão ou usar 15 minutos como fallback
                $estimatedTime = $defaultTimes[$category->name] ?? 15;
                
                CategoryPrepTime::create([
                    'category_id' => $category->id,
                    'estimated_minutes' => $estimatedTime
                ]);

                $this->command->info("✓ Tempo estimado definido para '{$category->name}': {$estimatedTime} minutos");
            } else {
                $this->command->info("→ '{$category->name}' já possui tempo estimado: {$existingPrepTime->estimated_minutes} minutos");
            }
        }

        $this->command->info("\n✅ Tempos estimados de preparo configurados com sucesso!");
    }
}