<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'unit_cost',
        'reference_type',
        'reference_id',
        'user_id',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime',
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
