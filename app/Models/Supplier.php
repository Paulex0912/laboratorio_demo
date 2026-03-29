<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\PurchaseOrder;

class Supplier extends Model
{
    protected $fillable = [
        'business_name', 'ruc', 'contact_name', 'phone', 'email', 'address', 'payment_term_days'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class , 'product_suppliers')
            ->withPivot('unit_price', 'lead_time_days', 'is_preferred')
            ->withTimestamps();
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
