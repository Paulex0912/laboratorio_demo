<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseOrder;
use App\Models\Product;

class PurchaseOrderLine extends Model
{
    protected $fillable = [
        'purchase_order_id', 'product_id', 'quantity', 'unit_price', 'lot', 'expiry_date'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
