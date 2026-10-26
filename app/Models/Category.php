<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Category extends Model
{
    use Auditable;

    protected $fillable = ['name'];

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
