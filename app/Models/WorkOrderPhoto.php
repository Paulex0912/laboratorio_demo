<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderPhoto extends Model
{
    protected $fillable = [
        'work_order_id',
        'photo_path',
        'comment',
    ];

    public function order()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }
}
