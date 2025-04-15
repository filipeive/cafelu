<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    // Uma categoria pode ter muitos produtos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
