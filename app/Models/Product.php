<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;
use App\Traits\BelongsToTenant;

class Product extends Model
{
    use Auditable, BelongsToTenant;
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'price',
        'purchase_price',
        'selling_price',
        'stock_quantity',
        'category_id',
        'type',
        'unit',
        'min_stock_level',
        'image',
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

    protected $appends = [
        'image_url',
    ];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('self_service_categories');
            \Illuminate\Support\Facades\Cache::forget('self_service_featured');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('self_service_categories');
            \Illuminate\Support\Facades\Cache::forget('self_service_featured');
        });
    }

    public function getImageUrlAttribute()
    {
        $path = $this->image_path ?: $this->image;
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            $subPath = substr($cleanPath, 8);
            if (file_exists(public_path('storage/' . $subPath))) {
                return asset('storage/' . $subPath);
            }
        }

        if (file_exists(public_path('storage/' . $cleanPath))) {
            return asset('storage/' . $cleanPath);
        }

        if (!str_starts_with($cleanPath, 'products/') && file_exists(public_path('storage/products/' . $cleanPath))) {
            return asset('storage/products/' . $cleanPath);
        }

        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        if (file_exists(public_path('assets/images/' . $cleanPath))) {
            return asset('assets/images/' . $cleanPath);
        }

        if (file_exists(storage_path('app/public/' . $cleanPath))) {
            return asset('storage/' . $cleanPath);
        }

        // Return standard storage URL
        return asset('storage/' . (str_starts_with($cleanPath, 'products/') ? $cleanPath : $cleanPath));
    }

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
        if ($this->stock_quantity > 10)
            return 'high';
        if ($this->stock_quantity > 5)
            return 'medium';
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