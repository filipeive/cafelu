<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Category extends Model
{
    use Auditable;

    protected $fillable = ['name'];

    // Uma categoria pode ter muitos produtos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
