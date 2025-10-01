<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPrepTime extends Model
{
    protected $fillable = [
        'category_id',
        'estimated_minutes'
    ];

    protected $casts = [
        'estimated_minutes' => 'integer'
    ];

    /**
     * Relacionamento com Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Obter tempo estimado para uma categoria
     */
    public static function getEstimatedTime($categoryId)
    {
        $prepTime = static::where('category_id', $categoryId)->first();
        return $prepTime ? $prepTime->estimated_minutes : 15; // 15 minutos como padrão
    }

    /**
     * Atualizar tempo estimado baseado em métricas reais
     */
    public static function updateFromMetrics($categoryId)
    {
        $avgTime = KitchenMetric::whereHas('order.items.product', function($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        })
        ->whereNotNull('total_prep_time')
        ->where('created_at', '>=', now()->subDays(30))
        ->avg('total_prep_time');

        if ($avgTime) {
            static::updateOrCreate(
                ['category_id' => $categoryId],
                ['estimated_minutes' => round($avgTime)]
            );
        }
    }
}