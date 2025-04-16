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
        'group_id',
        'is_main',
        'merged_capacity',
        'position_x',
        'position_y'
    ];

    /**
     * Verifica se a mesa tem pedidos ativos
     *
     * @return bool
     */
    public function activeOrder()
    {
        return $this->orders()
                    ->whereIn('status', ['active', 'completed'])
                    ->latest()
                    ->first();
    }

    public function hasActiveOrder()
    {
        return $this->orders()
                    ->whereIn('status', ['active', 'completed'])
                    ->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($table) {
            if ($table->isDirty('group_id') && $table->group_id === null) {
                $table->is_main = false;
                $table->merged_capacity = null;
            }
        });
    }
    //
    public function getStatusAttribute($value)
    {
        // Se tiver um pedido ativo, a mesa está ocupada
        if ($this->hasActiveOrder()) {
            return 'occupied';
        }
        return $value;
    }
    /**
     * Retorna o pedido ativo da mesa
     *
     * @return \App\Models\Order|null
     */
    public function getActiveOrder()
    {
        return $this->orders()->where('status', 'active')->first();
    }

    /**
     * Relationship com Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function groupedTables()
    {
        return $this->hasMany(Table::class, 'group_id', 'group_id')
                    ->where('id', '!=', $this->id);
    }

    public function getGroupedTablesNumbersAttribute()
    {
        if (!$this->group_id) {
            return null;
        }
        
        return $this->groupedTables()
                    ->pluck('number')
                    ->sort()
                    ->implode(', ');
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