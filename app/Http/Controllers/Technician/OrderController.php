<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = WorkOrder::with(['patient', 'materials.product'])
            ->where('technician_id', auth()->id())
            ->whereIn('status', ['pendiente', 'en_proceso', 'terminado'])
            ->oldest('due_date')
            ->paginate(10);

        $products = \App\Models\Product::where('stock_current', '>', 0)->get();

        return view('technician.orders.index', compact('orders', 'products'));
    }

    public function updateStatus(Request $request, WorkOrder $order)
    {
        // Verificar que la orden pertenezca al técnico autenticado
        if ($order->technician_id !== auth()->id()) {
            abort(403, 'No autorizado.');
        }

        $validated = $request->validate([
            'status' => 'required|in:en_proceso,terminado,entregado',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        // Guardar log
        $order->logs()->create([
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        $order->status = $newStatus;
        if ($newStatus === 'entregado') {
            $order->delivered_at = now();
        }
        $order->save();

        return back()->with('success', 'Estado de la orden actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
    //
    }
}
