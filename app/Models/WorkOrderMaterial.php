<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderMaterial extends Model
{
    protected $fillable = [
        'work_order_id',
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class , 'work_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
}
