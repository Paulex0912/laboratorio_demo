<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Quote;
use App\Models\WorkType;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('patient', 'creator')->latest()->paginate(15);
        return view('reception.quotes.index', compact('quotes'));
    }

    public function create()
    {
        $patients = Patient::all();
        $workTypes = WorkType::all();
        return view('reception.quotes.form', compact('patients', 'workTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'valid_until' => 'required|date|after_or_equal:today',
            'items' => 'required|array|min:1',
            'items.*.work_type_id' => 'required|exists:work_types,id',
            'items.*.type_name' => 'required|string',
            'items.*.material' => 'nullable|string',
            'items.*.color' => 'nullable|string',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $subtotal = collect($validated['items'])->sum('price');
        // Simple logic for IGV if needed, leaving it 0 for now or calculating 18%
        $igv = 0;
        $total = $subtotal + $igv;

        Quote::create([
            'patient_id' => $validated['patient_id'],
            'lines_json' => $validated['items'],
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $total,
            'valid_until' => $validated['valid_until'],
            'status' => 'borrador',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('quotes.index')->with('success', 'Cotización creada exitosamente.');
    }

    public function edit(Quote $quote)
    {
        if ($quote->status === 'aprobada') {
            return redirect()->route('quotes.index')->with('error', 'No se puede editar una cotización aprobada.');
        }

        $patients = Patient::all();
        $workTypes = WorkType::all();
        return view('reception.quotes.form', compact('quote', 'patients', 'workTypes'));
    }

    public function update(Request $request, Quote $quote)
    {
        if ($quote->status === 'aprobada') {
            return redirect()->route('quotes.index')->with('error', 'No se puede modificar una cotización aprobada.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'valid_until' => 'required|date',
            'status' => 'required|in:borrador,enviada,rechazada,vencida',
            'items' => 'required|array|min:1',
            'items.*.work_type_id' => 'required|exists:work_types,id',
            'items.*.type_name' => 'required|string',
            'items.*.material' => 'nullable|string',
            'items.*.color' => 'nullable|string',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $subtotal = collect($validated['items'])->sum('price');
        $total = $subtotal;

        $quote->update([
            'patient_id' => $validated['patient_id'],
            'lines_json' => $validated['items'],
            'subtotal' => $subtotal,
            'total' => $total,
            'valid_until' => $validated['valid_until'],
            'status' => $validated['status']
        ]);

        return redirect()->route('quotes.index')->with('success', 'Cotización actualizada.');
    }

    public function destroy(Quote $quote)
    {
        if ($quote->status === 'aprobada') {
            return redirect()->route('quotes.index')->with('error', 'No se puede eliminar una cotización aprobada.');
        }
        $quote->delete();
        return redirect()->route('quotes.index')->with('success', 'Cotización eliminada.');
    }

    public function approve(Quote $quote)
    {
        if ($quote->status === 'aprobada') {
            return redirect()->back()->with('error', 'La cotización ya estaba aprobada.');
        }

        DB::transaction(function () use ($quote) {
            $quote->update(['status' => 'aprobada']);

            $order = WorkOrder::create([
                'patient_id' => $quote->patient_id,
                'type' => 'Generado desde Cotización #' . $quote->id,
                'amount' => $quote->total,
                'status' => 'Pendiente',
                'reception_date' => now(),
                'delivery_date' => now()->addDays(3),
                'color' => null,
                'material' => null,
            ]);

            foreach ($quote->lines_json as $item) {
                WorkOrderItem::create([
                    'work_order_id' => $order->id,
                    'work_type_id' => $item['work_type_id'] ?? null,
                    'type_name' => $item['type_name'] ?? 'Trabajo',
                    'material' => $item['material'] ?? null,
                    'color' => $item['color'] ?? null,
                    'price' => $item['price'] ?? 0,
                ]);
            }
        });

        return redirect()->route('orders.index')->with('success', 'Cotización aprobada. Orden de Trabajo generada exitosamente.');
    }

    public function pdf(Quote $quote)
    {
        $quote->load(['patient', 'creator']);

        $pdf = Pdf::loadView('reception.quotes.pdf', compact('quote'));

        return $pdf->download('Cotizacion_JoelDent_QT_' . str_pad($quote->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }
}
