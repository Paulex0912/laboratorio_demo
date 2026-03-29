<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\User;

class EmployeeDocument extends Model
{
    protected $fillable = [
        'employee_id', 'type', 'file_path', 'expiry_date', 'uploaded_by'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class , 'uploaded_by');
    }
}
