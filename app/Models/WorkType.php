<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'default_price',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
    ];
}
