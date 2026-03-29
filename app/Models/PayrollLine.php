<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollLine extends Model
{
    protected $fillable = [
        'payroll_id', 'employee_id', 'gross_salary', 'family_allowance',
        'overtime', 'tardiness_discount', 'afp_discount', 'ir_discount',
        'net_salary', 'essalud', 'cts', 'gratification'
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
