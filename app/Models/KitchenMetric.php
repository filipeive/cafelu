<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenMetric extends Model
{
    protected $fillable = [
        'order_id',
        'order_received_at',
        'preparation_started_at',
        'preparation_completed_at',
        'order_delivered_at',
        'total_prep_time',
        'items_count',
        'items_breakdown'
    ];

    protected $casts = [
        'order_received_at' => 'datetime',
        'preparation_started_at' => 'datetime',
        'preparation_completed_at' => 'datetime',
        'order_delivered_at' => 'datetime',
        'items_breakdown' => 'array',
        'total_prep_time' => 'integer',
        'items_count' => 'integer'
    ];

    /**
     * Relacionamento com Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Calcular tempo médio de preparo
     */
    public static function getAveragePreparationTime($days = 7)
    {
        return static::whereNotNull('total_prep_time')
            ->where('created_at', '>=', now()->subDays($days))
            ->avg('total_prep_time');
    }

    /**
     * Obter métricas do dia
     */
    public static function getTodayMetrics()
    {
        $today = static::whereDate('created_at', today());

        return [
            'orders_completed' => $today->whereNotNull('preparation_completed_at')->count(),
            'average_prep_time' => round($today->avg('total_prep_time'), 1),
            'fastest_prep_time' => $today->min('total_prep_time'),
            'slowest_prep_time' => $today->max('total_prep_time'),
            'total_items_prepared' => $today->sum('items_count')
        ];
    }

    /**
     * Registrar métrica para um pedido
     */
    public static function recordOrderMetric(Order $order)
    {
        $items = $order->items;
        
        $prepStarted = $items->whereNotNull('started_at')->min('started_at');
        $prepCompleted = $items->whereNotNull('finished_at')->max('finished_at');
        
        $totalPrepTime = null;
        if ($prepStarted && $prepCompleted) {
            $totalPrepTime = $prepStarted->diffInMinutes($prepCompleted);
        }

        return static::create([
            'order_id' => $order->id,
            'order_received_at' => $order->created_at,
            'preparation_started_at' => $prepStarted,
            'preparation_completed_at' => $prepCompleted,
            'order_delivered_at' => $order->completed_at,
            'total_prep_time' => $totalPrepTime,
            'items_count' => $items->count(),
            'items_breakdown' => $items->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'category' => $item->product->category->name ?? 'Outros',
                    'quantity' => $item->quantity,
                    'prep_time' => $item->started_at && $item->finished_at 
                        ? $item->started_at->diffInMinutes($item->finished_at)
                        : null
                ];
            })->toArray()
        ]);
    }
}