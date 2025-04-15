<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id', 'product_id', 'quantity', 'unit_price'
    ];

    // A sale item belongs to a sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // A sale item belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}