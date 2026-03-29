<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'unit_measure',
        'stock_current',
        'stock_min',
        'stock_max',
        'cost_price',
        'category_id',
        'image_path'
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class , 'category_id');
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class , 'product_id');
    }
}
