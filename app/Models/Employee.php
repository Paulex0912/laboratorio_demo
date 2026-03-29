<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Area;
use App\Models\User;
use App\Models\EmployeeContract;
use App\Models\Attendance;
use App\Models\EmployeeDocument;

class Employee extends Model
{
    protected $fillable = [
        'name', 'dni', 'birthdate', 'phone', 'email', 'address',
        'photo', 'position', 'area_id', 'start_date', 'user_id'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'start_date' => 'date',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contracts()
    {
        return $this->hasMany(EmployeeContract::class);
    }

    public function currentContract()
    {
        return $this->hasOne(EmployeeContract::class)->where('is_active', true)->latest('start_date');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }
}
