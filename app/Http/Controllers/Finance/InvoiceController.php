<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\WorkOrder;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['patient'])->latest('issue_date')->paginate(15);
        return view('finance.invoices.index', compact('invoices'));
    }

    public function receivables(Request $request)
    {
        $query = Invoice::with(['patient', 'payments'])
            ->whereIn('status', ['pendiente', 'parcial']);

        // Filtro Básico (Opcional)
        if ($request->has('filter')) {
            if ($request->filter === 'expired') {
                $query->where('due_date', '<', now()->format('Y-m-d'));
            }
            elseif ($request->filter === 'soon') {
                $query->whereBetween('due_date', [now()->format('Y-m-d'), now()->addDays(3)->format('Y-m-d')]);
            }
        }

        $receivables = $query->orderBy('due_date', 'asc')->get();

        // Calcular Métricas Agrupadas
        $metrics = [
            'total_debt' => 0,
            'expired_debt' => 0,
            'soon_debt' => 0,
            'healthy_debt' => 0,
        ];

        $events = [];

        foreach ($receivables as $inv) {
            $balance = $inv->total - $inv->payments->sum('amount');
            $metrics['total_debt'] += $balance;

            if ($inv->due_date < now()->startOfDay()) {
                $metrics['expired_debt'] += $balance;
                $color = '#dc2626'; // Red
            }
            elseif ($inv->due_date <= now()->addDays(3)->endOfDay()) {
                $metrics['soon_debt'] += $balance;
                $color = '#d97706'; // Amber
            }
            else {
                $metrics['healthy_debt'] += $balance;
                $color = '#4f46e5'; // Indigo
            }

            // Adjuntamos saldo dinámico al objeto para la vista
            $inv->current_balance = $balance;

            // Add Event for Calendar
            $events[] = [
                'title' => $inv->patient->name . ' (S/ ' . number_format($balance, 2) . ')',
                'start' => $inv->due_date->format('Y-m-d'),
                'url' => route('invoices.show', $inv),
                'color' => $color,
            ];
        }

        return view('finance.invoices.receivables', compact('receivables', 'metrics', 'events'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        // Generar siguiente correlativo F001-XXXX
        $lastInvoice = Invoice::where('series', 'F001')->orderBy('number', 'desc')->first();
        $nextNumber = $lastInvoice ? str_pad((int)$lastInvoice->number + 1, 8, '0', STR_PAD_LEFT) : '00000001';

        return view('finance.invoices.create', compact('patients', 'nextNumber'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'payments', 'workOrder']);
        $totalPaid = $invoice->payments()->sum('amount');
        $balance = $invoice->total - $totalPaid;

        return view('finance.invoices.show', compact('invoice', 'totalPaid', 'balance'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'series' => 'required|string|size:4',
            'number' => 'required|string|size:8',
            'patient_id' => 'required|exists:patients,id',
            'subtotal' => 'required|numeric|min:0.01',
            'date_issued' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date_issued',
        ]);

        $igv = $validated['subtotal'] * 0.18;
        $total = $validated['subtotal'] + $igv;

        Invoice::create(array_merge($validated, [
            'igv' => $igv,
            'total' => $total,
            'status' => 'pendiente'
        ]));

        return redirect()->route('invoices.index')->with('success', 'Factura generada exitosamente.');
    }

    public function storePayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'paid_at' => 'required|date',
            'reference' => 'nullable|string'
        ]);

        $totalPaid = $invoice->payments()->sum('amount');
        $balance = $invoice->total - $totalPaid;

        if ($validated['amount'] > $balance) {
            return back()->withErrors(['amount' => 'El abono no puede superar el saldo pendiente de S/ ' . number_format($balance, 2)]);
        }

        $invoice->payments()->create($validated);

        // Actualizar estado si se pagó todo
        $newTotalPaid = $invoice->payments()->sum('amount');
        if ($newTotalPaid >= $invoice->total) {
            $invoice->update(['status' => 'pagada']);
        }
        else {
            $invoice->update(['status' => 'parcial']);
        }

        return back()->with('success', 'Abono registrado correctamente.');
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:anulada',
            'cancellation_reason' => 'required_if:status,anulada|string|max:255'
        ]);

        if ($invoice->status === 'pagada') {
            return back()->withErrors(['status' => 'No se puede anular una factura que ya ha sido pagada en su totalidad.']);
        }

        $invoice->update([
            'status' => 'anulada',
            'cancellation_reason' => $validated['cancellation_reason']
        ]);

        return back()->with('success', 'La factura ha sido anulada correctamente.');
    }
}
