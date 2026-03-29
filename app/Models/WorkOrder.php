<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = [
        'patient_id',
        'technician_id',
        'invoice_id',
        'status',
        'amount',
        'due_date',
        'delivered_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'delivered_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class , 'technician_id');
    }

    public function logs()
    {
        return $this->hasMany(WorkOrderLog::class)->latest();
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function materials()
    {
        return $this->hasMany(WorkOrderMaterial::class);
    }

    public function items()
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    public function photos()
    {
        return $this->hasMany(WorkOrderPhoto::class);
    }
}
