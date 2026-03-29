<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashClosure extends Model
{
    protected $fillable = [
        'date',
        'total_income',
        'total_expense',
        'balance',
    ];

    protected $casts = [
        'date' => 'date',
        'total_income' => 'decimal:2',
        'total_expense' => 'decimal:2',
        'balance' => 'decimal:2',
    ];
}
