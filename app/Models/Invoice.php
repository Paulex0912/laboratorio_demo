<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Invoice extends Model
{
    use Auditable;

    protected $fillable = [
        'patient_id',
        'invoice_type',
        'series_number',
        'discount_percentage',
        'issue_date',
        'subtotal',
        'igv',
        'total',
        'status',
        'issued_by'
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function accountReceivable()
    {
        return $this->hasOne(AccountReceivable::class);
    }
}
