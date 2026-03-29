<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee; // Added this line for the relationship

class EmployeeContract extends Model
{
    protected $fillable = [
        'employee_id', 'type', 'start_date', 'end_date', 'gross_salary',
        'labor_regime', 'afp_type', 'family_allowance', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'family_allowance' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
