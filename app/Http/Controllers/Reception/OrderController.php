<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Invoice;
use App\Models\AccountReceivable;
use App\Models\CustomerPayment;
use App\Models\CashMovement;
use App\Models\BankAccountMovement;
use App\Models\WorkOrderPhoto;
use App\Imports\OrdersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WorkOrder::with(['patient', 'technician', 'invoice.accountReceivable']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        
        return view('reception.orders.index', compact('orders'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new OrdersImport, $request->file('file'));
            return redirect()->route('orders.index')->with('success', 'Órdenes importadas correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error durante la importación: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $patients = \App\Models\Patient::orderBy('name')->get();
        // Cargar técnicos desde la planilla de personal (Módulo RRHH)
        $technicians = \App\Models\Employee::orderBy('name')->get();
        // Cargar catálogo de tipos de trabajo
        $workTypes = \App\Models\WorkType::orderBy('name')->get();

        return view('reception.orders.form', compact('patients', 'technicians', 'workTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'technician_id' => 'nullable|exists:employees,id',
            'due_date' => 'required|date|after_or_equal:today',
            'items' => 'required|array|min:1',
            'items.*.type_name' => 'required|string|max:255',
            'items.*.material' => 'nullable|string|max:255',
            'items.*.color' => 'nullable|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $totalAmount = collect($validated['items'])->sum('price');

        // Create main WorkOrder
        $order = WorkOrder::create([
            'patient_id' => $validated['patient_id'],
            'technician_id' => $validated['technician_id'],
            'due_date' => $validated['due_date'],
            'amount' => $totalAmount,
            'status' => 'pendiente',
        ]);

        // Create WorkOrderItems
        foreach ($validated['items'] as $item) {
            $workType = \App\Models\WorkType::where('name', $item['type_name'])->first();
            $order->items()->create([
                'work_type_id' => $workType ? $workType->id : null,
                'type_name' => $item['type_name'],
                'material' => $item['material'] ?? null,
                'color' => $item['color'] ?? null,
                'price' => $item['price'],
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Orden de trabajo creada correctamente.');
    }

    public function show(WorkOrder $order)
    {
        return view('reception.orders.show', compact('order'));
    }

    public function edit(WorkOrder $order)
    {
        $order->load(['items', 'patient', 'technician']);
        $patients = \App\Models\Patient::orderBy('name')->get();
        $technicians = \App\Models\Employee::orderBy('name')->get();
        $workTypes = \App\Models\WorkType::orderBy('name')->get();

        return view('reception.orders.form', compact('order', 'patients', 'technicians', 'workTypes'));
    }

    public function update(Request $request, WorkOrder $order)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'technician_id' => 'nullable|exists:employees,id',
            'due_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:work_order_items,id',
            'items.*.type_name' => 'required|string|max:255',
            'items.*.material' => 'nullable|string|max:255',
            'items.*.color' => 'nullable|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validated, $order) {
                $totalAmount = collect($validated['items'])->sum('price');
                $order->update([
                    'patient_id' => $validated['patient_id'],
                    'technician_id' => $validated['technician_id'],
                    'due_date' => $validated['due_date'],
                    'amount' => $totalAmount,
                ]);

                // Update items: remove those that are not in the validated data, update existing, create new
                $existingItemIds = collect($validated['items'])->pluck('id')->filter()->toArray();
                $order->items()->whereNotIn('id', $existingItemIds)->delete();

                foreach ($validated['items'] as $itemData) {
                    $workType = \App\Models\WorkType::where('name', $itemData['type_name'])->first();
                    $order->items()->updateOrCreate(
                        ['id' => $itemData['id'] ?? null],
                        [
                            'work_type_id' => $workType ? $workType->id : null,
                            'type_name' => $itemData['type_name'],
                            'material' => $itemData['material'] ?? null,
                            'color' => $itemData['color'] ?? null,
                            'price' => $itemData['price'],
                        ]
                    );
                }
            });

            return redirect()->route('orders.index')->with('success', 'Orden de trabajo actualizada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error actualizando la orden: ' . $e->getMessage())->withInput();
        }
    }

    public function cancel(WorkOrder $order)
    {
        abort_if(in_array($order->status, ['entregado', 'anulado']), 403, 'No se puede anular en este estado.');
        
        $order->update(['status' => 'anulado']);
        return redirect()->route('orders.index')->with('success', 'Orden anulada correctamente.');
    }

    public function changeStatus(Request $request, WorkOrder $order)
    {
        $request->validate([
            'status' => 'required|in:pendiente,en_proceso,terminado'
        ]);

        abort_if(in_array($order->status, ['entregado', 'anulado']), 403, 'No se puede cambiar el estado de una orden entregada o anulada.');

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Estado de la orden actualizado correctamente.');
    }

    public function storePhoto(Request $request, WorkOrder $order)
    {
        $request->validate([
            'photo' => 'required|image|max:2048', // Max 2MB
            'comment' => 'nullable|string|max:1000',
        ]);

        $path = $request->file('photo')->store('work_order_photos', 'public');

        $order->photos()->create([
            'photo_path' => $path,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Foto subida correctamente.');
    }

    public function destroyPhoto(WorkOrderPhoto $photo)
    {
        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return back()->with('success', 'Foto eliminada correctamente.');
    }

    public function checkout(WorkOrder $order)
    {
        abort_if($order->status !== 'terminado', 403, 'La orden no está terminada.');

        $order->load(['patient', 'items']);

        // Buscar otras órdenes terminadas y sin factura para el mismo paciente
        $otherPendingOrders = WorkOrder::where('patient_id', $order->patient_id)
            ->where('status', 'terminado')
            ->whereNull('invoice_id')
            ->where('id', '!=', $order->id)
            ->with('items')
            ->get();

        // Cuentas de banco para transferencia
        $banks = \App\Models\BankAccount::where('status', 'activo')->get();

        return view('reception.orders.checkout', compact('order', 'otherPendingOrders', 'banks'));
    }

    public function processCheckout(Request $request, WorkOrder $order)
    {
        abort_if($order->status !== 'terminado', 403, 'La orden no está terminada.');

        $validated = $request->validate([
            'included_orders' => 'nullable|array',
            'included_orders.*' => 'exists:work_orders,id',
            'invoice_type' => 'required|string|in:Boleta,Factura,Recibo',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'payment_method' => 'required|string|in:Efectivo,Transferencia,Tarjeta,Cheque',
            'amount_paid' => 'required|numeric|min:0',
            'bank_account_id' => 'required_if:payment_method,Transferencia',
            'reference_number' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated, $order) {

                $orderIds = $validated['included_orders'] ?? [];
                if (!in_array($order->id, $orderIds)) {
                    $orderIds[] = $order->id;
                }

                $ordersToBill = WorkOrder::whereIn('id', $orderIds)
                    ->where('patient_id', $order->patient_id)
                    ->where('status', 'terminado')
                    ->whereNull('invoice_id')
                    ->lockForUpdate()
                    ->get();
                
                $subtotal = $ordersToBill->sum('amount');
                $discountPercentage = $validated['discount_percentage'] ?? 0;
                $discountAmount = $subtotal * ($discountPercentage / 100);
                $totalFixed = $subtotal - $discountAmount;

                // 1. Generar la Factura/Boleta (Invoice)
                $invoice = Invoice::create([
                    'patient_id' => $order->patient_id,
                    'invoice_type' => $validated['invoice_type'],
                    'discount_percentage' => $discountPercentage,
                    'issue_date' => now(),
                    'subtotal' => $subtotal,
                    'igv' => 0, // Simplificado,
                    'total' => $totalFixed,
                    'status' => 'emitida',
                    'issued_by' => auth()->id(),
                ]);

                // Asociar órdenes a la factura
                foreach($ordersToBill as $o) {
                    $o->update([
                        'invoice_id' => $invoice->id,
                        'status' => 'entregado',
                    ]);
                }

                // 2. Generar la Cuenta por Cobrar (Deuda)
                $receivable = AccountReceivable::create([
                    'patient_id' => $order->patient_id,
                    'invoice_id' => $invoice->id,
                    'total_amount' => $totalFixed,
                    'paid_amount' => $validated['amount_paid'],
                    'balance' => $totalFixed - $validated['amount_paid'],
                    'due_date' => now()->addDays(30), // Vencimiento referencial
                    'status' => ($totalFixed <= $validated['amount_paid']) ? 'pagado' : 'parcial',
                ]);

                // 3. Registrar el Pago del Cliente si abonó algo
                if ($validated['amount_paid'] > 0) {
                    $payment = CustomerPayment::create([
                        'account_receivable_id' => $receivable->id,
                        'patient_id' => $order->patient_id,
                        'amount' => $validated['amount_paid'],
                        'payment_method' => $validated['payment_method'],
                        'reference_number' => $validated['reference_number'] ?? null,
                        'payment_date' => now(),
                        'received_by' => auth()->id(),
                        'notes' => 'Cobro en Recepción OT #' . $order->id,
                    ]);

                    // 4. Mover el dinero a Tesorería o Bancos
                    if ($validated['payment_method'] === 'Efectivo') {
                        CashMovement::create([
                            'type' => 'ingreso',
                            'amount' => $validated['amount_paid'],
                            'description' => 'Cobro Efectivo OT #' . $order->id . ' - ' . $order->patient->name,
                            'reference_type' => CustomerPayment::class ,
                            'reference_id' => $payment->id,
                            'created_by' => auth()->id(),
                        ]);
                    }
                    elseif ($validated['payment_method'] === 'Transferencia' && isset($validated['bank_account_id'])) {
                        BankAccountMovement::create([
                            'bank_account_id' => $validated['bank_account_id'],
                            'type' => 'ingreso',
                            'amount' => $validated['amount_paid'],
                            'date' => now(),
                            'description' => 'Transferencia Cliente OT #' . $order->id,
                            'reference_type' => CustomerPayment::class ,
                            'reference_id' => $payment->id,
                            'user_id' => auth()->id(),
                        ]);
                    }
                }

                // 5. Marcar Orden como Entregada
                $order->update(['status' => 'entregado']);

            });

            return redirect()->route('orders.index')->with('success', 'Orden entregada y facturada correctamente.');

        }
        catch (\Exception $e) {
            return back()->with('error', 'Error en el cobro: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(WorkOrder $order)
    {
    //
    }
}
