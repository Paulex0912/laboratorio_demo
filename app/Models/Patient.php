<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name',
        'dni',
        'ruc',
        'phone',
        'email',
        'dental_notes',
        'observations',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class , 'created_by');
    }

    public function accountReceivables()
    {
        return $this->hasMany(AccountReceivable::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
