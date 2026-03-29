<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankMovement extends Model
{
    protected $fillable = [
        'bank_account_id',
        'type',
        'amount',
        'description',
        'date',
        'reference',
        'reconciled',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'reconciled' => 'boolean',
    ];

    public function account()
    {
        return $this->belongsTo(BankAccount::class , 'bank_account_id');
    }
}
