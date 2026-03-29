<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Product;
use App\Models\WorkOrderMaterial;
use App\Models\InventoryMovement;
use App\Events\StockBelowMinimum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WorkOrderMaterialController extends Controller
{
    public function store(Request $request, WorkOrder $order)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock_current < $request->quantity) {
            return back()->with('error', 'Stock insuficiente para el material seleccionado.');
        }

        DB::transaction(function () use ($request, $order, $product) {
            // 1. Agregar material a la orden
            WorkOrderMaterial::create([
                'work_order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);

            // 2. Registrar el movimiento de salida (Kardex)
            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $request->quantity,
                'unit_cost' => $product->cost_price,
                'reference_type' => WorkOrder::class ,
                'reference_id' => $order->id,
                'user_id' => Auth::id() ?? 1,
                'date' => now()
            ]);

            // 3. Descontar stock
            $product->stock_current -= $request->quantity;
            $product->save();

            // 4. Disparar evento si cruza el mínimo
            if ($product->stock_current < $product->stock_min) {
                event(new StockBelowMinimum($product));
            }
        });

        return back()->with('success', 'Material registrado correctamente en la orden.');
    }
}
