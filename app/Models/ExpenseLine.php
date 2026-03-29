<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseLine extends Model
{
    protected $fillable = [
        'expense_report_id',
        'category_id',
        'amount',
        'description',
        'receipt_path',
    ];

    public function report()
    {
        return $this->belongsTo(ExpenseReport::class , 'expense_report_id');
    }

    public function category()
    {
        return $this->belongsTo(CashCategory::class , 'category_id');
    }
}
