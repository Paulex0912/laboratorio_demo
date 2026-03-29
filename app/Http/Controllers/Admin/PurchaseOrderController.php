<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with('supplier', 'creator')->latest()->paginate(10);
        return view('admin.purchases.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('admin.purchases.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'borrador';

        PurchaseOrder::create($validated);

        return redirect()->route('admin.purchases.index')->with('success', 'Orden de compra borrador creada.');
    }
}
