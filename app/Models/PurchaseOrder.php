<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Models\PurchaseOrderLine;
use App\Models\User;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'supplier_id', 'status', 'total', 'notes', 'expected_date', 'created_by'
    ];

    protected $casts = [
        'expected_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lines()
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class , 'created_by');
    }
}
