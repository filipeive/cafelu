<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'stock_quantity', 
        'category_id', 'image_path', 'menu_id', 'is_active'
    ];

    protected $casts = [
    'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean'
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