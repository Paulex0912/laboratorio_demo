<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model
{
    protected $fillable = [
        'work_order_id',
        'work_type_id',
        'type_name',
        'material',
        'color',
        'price',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
