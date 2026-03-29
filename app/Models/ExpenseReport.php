<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ExpenseReport extends Model
{
    use Auditable;

    protected $fillable = [
        'user_id',
        'title',
        'status',
        'total',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class , 'approved_by');
    }

    public function lines()
    {
        return $this->hasMany(ExpenseLine::class);
    }
}
