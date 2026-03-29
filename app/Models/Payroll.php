<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PayrollLine;

class Payroll extends Model
{
    protected $fillable = [
        'period_month', 'period_year', 'status', 'total_gross',
        'total_net', 'total_employer_cost', 'approved_by', 'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function aprover()
    {
        return $this->belongsTo(User::class , 'approved_by');
    }

    public function lines()
    {
        return $this->hasMany(PayrollLine::class);
    }
}
