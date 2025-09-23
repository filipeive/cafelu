<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'type',
        'purchase_price',
        'selling_price',
        'stock_quantity',
        'min_stock_level',
        'unit',
        'is_active',
        'deleted_at',
        'original_name',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relacionamentos
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Métodos de negócio
    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function updateStock(int $quantity, string $type = 'out'): void
    {
        if ($type === 'out') {
            $this->decrement('stock_quantity', $quantity);
        } else {
            $this->increment('stock_quantity', $quantity);
        }
    }

    // 👇 Accessor: exibe nome com marcação se estiver excluído
    public function getNameAttribute($value)
    {
        if ($this->trashed()) {
            return $value . ' 🚫 (EXCLUÍDO)';
        }
        return $value;
    }

    // 👇 Método para marcar como excluído (soft delete) corretamente
    public function markAsDeleted()
    {
        if (!$this->trashed()) {
            $this->original_name = $this->name;
            $this->is_active = false;
            $this->save();
            $this->delete(); // Define deleted_at = now()
        }
    }

    // 👇 Formata o preço para exibição
    public function getFormattedPriceAttribute()
    {
        // Prioriza selling_price (se for reprografia ou produto normal)
        if (!is_null($this->selling_price)) {
            return number_format($this->selling_price, 2, ',', '.');
        }

        // Fallback para price (caso catering ou outro tipo)
        if (!is_null($this->price ?? null)) {
            return number_format($this->price, 2, ',', '.');
        }

        return '0,00';
    }

    // 👇 Retorna status visual do estoque
    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity > 10) return 'high';
        if ($this->stock_quantity > 5) return 'medium';
        return 'low';
    }

    // 👇 Getter inteligente para selling_price (com fallback para price)
    public function getSellingPriceAttribute()
    {
        return $this->attributes['selling_price'] ?? ($this->attributes['price'] ?? null);
    }
}