<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashMovement extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'category_id',
        'ref_doc',
        'cashier_id',
        'date',
        'notes',
        'receipt_path',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(CashCategory::class , 'category_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class , 'cashier_id');
    }
}
