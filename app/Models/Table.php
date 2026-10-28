<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Auditable;
use App\Traits\BelongsToTenant;

class Table extends Model
{
    use HasFactory, SoftDeletes, Auditable, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'number',
        'capacity',
        'status',
        'group_id',
        'is_main',
        'merged_capacity',
        'position_x',
        'position_y',
        'is_temporary'
    ];

    protected $appends = [
        'name',
        'qr_code_url',
    ];

    public function getNameAttribute()
    {
        return 'Mesa ' . $this->number;
    }

    public function getQrCodeUrlAttribute(): string
    {
        try {
            $tenantSlug = $this->tenant?->slug ?? (function_exists('current_tenant') ? current_tenant()?->slug : null);
            if ($tenantSlug) {
                return route('welcome.tenant.table', ['tenant' => $tenantSlug, 'table' => $this->number]);
            }
            return route('welcome.table.short', ['table' => $this->number]);
        } catch (\Throwable $e) {
            return url('/m/' . $this->number);
        }
    }

    /**
     * Verifica se a mesa tem pedidos ativos
     *
     * @return \App\Models\Order|null
     */
    public function activeOrder()
    {
        if ($this->relationLoaded('orders')) {
            return $this->orders->first(function ($order) {
                return in_array($order->status, ['active', 'preparing', 'ready', 'delivered'])
                    || ($order->status === 'completed' && $order->payment_status !== 'paid');
            });
        }

        return $this->orders()
            ->where(function ($query) {
                $query->whereIn('status', ['active', 'preparing', 'ready', 'delivered'])
                    ->orWhere(function ($q) {
                        $q->where('status', 'completed')
                          ->where('payment_status', '!=', 'paid');
                    });
            })
            ->latest()
            ->first();
    }

    public function hasActiveOrder()
    {
        if ($this->relationLoaded('orders')) {
            return $this->orders->contains(function ($order) {
                return in_array($order->status, ['active', 'preparing', 'ready', 'delivered'])
                    || ($order->status === 'completed' && $order->payment_status !== 'paid');
            });
        }

        return $this->orders()
            ->where(function ($query) {
                $query->whereIn('status', ['active', 'preparing', 'ready', 'delivered'])
                    ->orWhere(function ($q) {
                        $q->where('status', 'completed')
                          ->where('payment_status', '!=', 'paid');
                    });
            })
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