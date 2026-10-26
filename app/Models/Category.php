<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;
use App\Traits\BelongsToTenant;

class Category extends Model
{
    use Auditable, BelongsToTenant;

    protected $fillable = ['tenant_id', 'name'];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('self_service_categories');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('self_service_categories');
        });
    }

    // Uma categoria pode ter muitos produtos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
