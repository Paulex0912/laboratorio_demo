<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderLog extends Model
{
    protected $fillable = [
        'work_order_id',
        'from_status',
        'to_status',
        'user_id',
        'comment',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
