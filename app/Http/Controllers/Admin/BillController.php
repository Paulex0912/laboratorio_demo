<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Supplier;
use App\Models\GeneralCategory;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::with(['supplier', 'generalCategory', 'purchaseOrder'])->latest('issue_date')->paginate(15);
        return view('admin.bills.index', compact('bills'));
    }

    public function calendar()
    {
        return view('admin.bills.calendar');
    }

    public function calendarData(Request $request)
    {
        $start = $request->start ? \Carbon\Carbon::parse($request->start) : now()->startOfMonth();
        $end = $request->end ? \Carbon\Carbon::parse($request->end) : now()->endOfMonth();

        $bills = Bill::with('supplier')
            ->whereBetween('due_date', [$start, $end])
            ->whereIn('status', ['pendiente', 'parcial'])
            ->get();

        $events = $bills->map(function ($bill) {
            return [
                'id' => $bill->id,
                'title' => 'S/ ' . number_format($bill->balance, 2) . ' - ' . $bill->supplier->business_name,
                'start' => $bill->due_date->format('Y-m-d'),
                'url' => route('admin.bills.show', $bill->id),
                'color' => $bill->due_date->isPast() ? '#EF4444' : '#F59E0B', // Red if overdue, amber if upcoming
                'allDay' => true,
            ];
        });

        return response()->json($events);
    }
    public function create()
    {
        $suppliers = Supplier::orderBy('business_name')->get();
        $categories = GeneralCategory::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::whereNotIn('status', ['anulada', 'recibida'])->get(); // Orders you can attach a bill to

        return view('admin.bills.create', compact('suppliers', 'categories', 'purchaseOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'general_category_id' => 'nullable|exists:general_categories,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'bill_number' => 'required|string|max:255|unique:bills,bill_number',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('invoice_file')) {
            $path = $request->file('invoice_file')->store('bills', 'public');
            $validated['invoice_file_path'] = $path;
        }

        $validated['balance'] = $validated['total_amount'];
        $validated['status'] = 'pendiente';

        $bill = Bill::create($validated);

        // If a purchase order is linked, we might want to update its status to "recibida"
        if ($bill->purchase_order_id) {
            $po = PurchaseOrder::find($bill->purchase_order_id);
            if ($po) {
                $po->update(['status' => 'recibida']);
                // Todo: automatically increase inventory based on the PO lines
                $this->receivePurchaseOrderInventory($po);
            }
        }

        return redirect()->route('admin.bills.index')->with('success', 'Compra (Bill) registrada correctamente.');
    }

    public function show(Bill $bill)
    {
        $bill->load(['supplier', 'generalCategory', 'purchaseOrder.lines.product']);
        return view('admin.bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        $suppliers = Supplier::orderBy('business_name')->get();
        $categories = GeneralCategory::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::whereNotIn('status', ['anulada', 'recibida'])
            ->orWhere('id', $bill->purchase_order_id)
            ->get();

        return view('admin.bills.edit', compact('bill', 'suppliers', 'categories', 'purchaseOrders'));
    }

    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'general_category_id' => 'nullable|exists:general_categories,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'bill_number' => 'required|string|max:255|unique:bills,bill_number,' . $bill->id,
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('invoice_file')) {
            $path = $request->file('invoice_file')->store('bills', 'public');
            $validated['invoice_file_path'] = $path;
        }

        // Only update balance if we haven't paid anything yet to keep it simple, 
        // or recalculate if there are payments (out of scope for now unless requested)
        if ($bill->status === 'pendiente') {
            $validated['balance'] = $validated['total_amount'];
        }

        $bill->update($validated);

        return redirect()->route('admin.bills.index')->with('success', 'Compra actualizada correctamente.');
    }

    public function destroy(Bill $bill)
    {
        if ($bill->status !== 'pendiente') {
            return redirect()->route('admin.bills.index')->with('error', 'No se puede eliminar una compra que ya tiene pagos registrados.');
        }

        if ($bill->purchase_order_id) {
            // Revert PO status
            $po = PurchaseOrder::find($bill->purchase_order_id);
            if ($po) {
                $po->update(['status' => 'aprobada']); // Or 'borrador'
                // Revert inventory is complex, skipping for MVP unless requested
            }
        }

        $bill->delete();
        return redirect()->route('admin.bills.index')->with('success', 'Compra eliminada.');
    }

    private function receivePurchaseOrderInventory(PurchaseOrder $po)
    {
        // For each line in the PO, increase the product stock
        foreach ($po->lines as $line) {
            if ($line->product_id) {
                $product = $line->product;
                $product->stock += $line->quantity;
                $product->save();

                // Record the inventory movement
                \App\Models\InventoryMovement::create([
                    'product_id' => $product->id,
                    'type' => 'ingreso',
                    'quantity' => $line->quantity,
                    'unit_cost' => $line->unit_price,
                    'reference_type' => \App\Models\PurchaseOrder::class,
                    'reference_id' => $po->id,
                    'user_id' => auth()->id(),
                    'date' => now(),
                ]);
            }
        }
    }
}
