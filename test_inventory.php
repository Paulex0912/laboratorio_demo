<?php

$product = \App\Models\Product::create([
    'code' => 'TEST-' . rand(1000, 9999),
    'name' => 'Resina de Prueba',
    'unit_measure' => 'gramo',
    'stock_current' => 10,
    'stock_min' => 3,
    'stock_max' => 50,
    'cost_price' => 20
]);

\App\Models\InventoryMovement::create([
    'product_id' => $product->id,
    'type' => 'in',
    'quantity' => 10,
    'unit_cost' => 20,
    'user_id' => 1,
    'date' => now()
]);

$order = \App\Models\WorkOrder::first();

if (!$order) {
    $order = \App\Models\WorkOrder::create([
        'patient_id' => \App\Models\Patient::first()->id ?? 1,
        'technician_id' => \App\Models\User::first()->id ?? 1,
        'status' => 'en_proceso',
        'type' => 'Corona',
        'material' => 'Zirconio',
        'due_date' => now()->addDays(2),
    ]);
}

echo "--- Antes del consumo ---\n";
echo "Stock: " . $product->stock_current . "\n";

// Simulando el controlador (transacción manual para el test)
\Illuminate\Support\Facades\DB::transaction(function () use ($order, $product) {
    \App\Models\WorkOrderMaterial::create([
        'work_order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 8
    ]);

    \App\Models\InventoryMovement::create([
        'product_id' => $product->id,
        'type' => 'out',
        'quantity' => 8,
        'unit_cost' => $product->cost_price,
        'reference_type' => \App\Models\WorkOrder::class ,
        'reference_id' => $order->id,
        'user_id' => 1,
        'date' => now()
    ]);

    $product->stock_current -= 8;
    $product->save();

    if ($product->stock_current < $product->stock_min) {
        event(new \App\Events\StockBelowMinimum($product));
    }
});

$product->refresh();
echo "--- Despues del consumo ---\n";
echo "Stock: " . $product->stock_current . "\n";
echo "Movimientos Kardex: " . $product->movements()->count() . "\n";
