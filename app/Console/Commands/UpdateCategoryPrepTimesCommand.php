<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\CategoryPrepTime;

class UpdateCategoryPrepTimesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kitchen:update-prep-times 
                            {--days=30 : Número de dias para calcular média}
                            {--category= : ID da categoria específica}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os tempos estimados de preparo baseado em métricas reais';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $categoryId = $this->option('category');

        $this->info("🔄 Atualizando tempos estimados de preparo...");
        $this->info("📊 Baseado nos últimos {$days} dias\n");

        if ($categoryId) {
            // Atualizar categoria específica
            $category = Category::find($categoryId);
            
            if (!$category) {
                $this->error("❌ Categoria ID {$categoryId} não encontrada");
                return 1;
            }

            $this->updateCategoryPrepTime($category, $days);
        } else {
            // Atualizar todas as categorias
            $categories = Category::where('is_active', true)->get();
            
            if ($categories->isEmpty()) {
                $this->warn("⚠️  Nenhuma categoria ativa encontrada");
                return 0;
            }

            $this->withProgressBar($categories, function ($category) use ($days) {
                $this->updateCategoryPrepTime($category, $days);
            });
            
            $this->newLine(2);
        }

        $this->info("✅ Tempos estimados atualizados com sucesso!");
        
        return 0;
    }

    /**
     * Atualizar tempo estimado de uma categoria
     */
    private function updateCategoryPrepTime(Category $category, int $days)
    {
        $oldTime = CategoryPrepTime::getEstimatedTime($category->id);
        
        CategoryPrepTime::updateFromMetrics($category->id);
        
        $newTime = CategoryPrepTime::getEstimatedTime($category->id);
        
        if ($oldTime != $newTime) {
            $this->line("  ✓ {$category->name}: {$oldTime}min → {$newTime}min");
        }
    }
}