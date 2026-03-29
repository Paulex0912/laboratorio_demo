<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['name', 'description', 'manager_employee_id'];

    public function manager()
    {
        return $this->belongsTo(Employee::class , 'manager_employee_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
