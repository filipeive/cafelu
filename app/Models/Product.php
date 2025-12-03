<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'name',
    'description',
    'purchase_price',
    'selling_price',
    'stock_quantity',
    'category_id',
    'type',
    'unit',
    'min_stock_level',
    'image_path',
    'is_active'
];

protected $casts = [
    'purchase_price' => 'decimal:2',
    'selling_price' => 'decimal:2',
    'stock_quantity' => 'integer',
    'is_active' => 'boolean',
    'min_stock_level' => 'integer'
];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, ',', '.');
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity > 10) return 'high';
        if ($this->stock_quantity > 5) return 'medium';
        return 'low';
    }

    // A product can be in many sale items
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // A product can be in many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}