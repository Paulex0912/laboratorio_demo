<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_receivable_id',
        'patient_id',
        'amount',
        'payment_method',
        'reference_number',
        'payment_date',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function accountReceivable()
    {
        return $this->belongsTo(AccountReceivable::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class , 'received_by');
    }
}
