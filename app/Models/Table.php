<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'capacity',
        'status',
        'is_main',
        'group_id',
        'merged_capacity'
    ];

    /**
     * Verifica se a mesa tem pedidos ativos
     *
     * @return bool
     */
    public function hasActiveOrders()
    {
        return $this->orders()->where('status', 'pending')->exists();
    }

    /**
     * Retorna o pedido ativo da mesa
     *
     * @return \App\Models\Order|null
     */
    public function getActiveOrder()
    {
        return $this->orders()->where('status', 'pending')->first();
    }

    /**
     * Relationship com Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope para filtrar mesas por status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar mesas por grupo
     */
    public function scopeByGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    /**
     * Scope para mesas principais
     */
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }
}