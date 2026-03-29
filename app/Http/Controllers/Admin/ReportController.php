<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\CashMovement;
use App\Exports\FinancialExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:invoices,cash_movements,bank_movements,work_orders',
            'format' => 'required|in:xlsx,csv',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $start = $request->start_date ?\Carbon\Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30)->startOfDay();
        $end = $request->end_date ?\Carbon\Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        if ($request->type === 'invoices') {
            $data = Invoice::with('patient')->whereBetween('created_at', [$start, $end])->orderBy('created_at', 'desc')->get();
            $export = new FinancialExport($data, 'invoices');
            $filename = 'Reporte_Facturacion_' . now()->format('Ymd_His') . '.' . $request->format;

            return Excel::download($export, $filename);
        }

        if ($request->type === 'cash_movements') {
            $data = CashMovement::with(['user', 'category'])->whereBetween('created_at', [$start, $end])->orderBy('date', 'desc')->get();
            $export = new FinancialExport($data, 'cash');
            $filename = 'Reporte_Caja_' . now()->format('Ymd_His') . '.' . $request->format;

            return Excel::download($export, $filename);
        }

        if ($request->type === 'bank_movements') {
            $data = \App\Models\BankMovement::with('account')->whereBetween('date', [$start, $end])->orderBy('date', 'desc')->get();
            $export = new FinancialExport($data, 'bank_movements');
            $filename = 'Reporte_Bancos_' . now()->format('Ymd_His') . '.' . $request->format;

            return Excel::download($export, $filename);
        }

        if ($request->type === 'work_orders') {
            $data = \App\Models\WorkOrder::with(['patient', 'doctor', 'technician'])->whereBetween('created_at', [$start, $end])->orderBy('created_at', 'desc')->get();
            $export = new FinancialExport($data, 'work_orders');
            $filename = 'Reporte_Ordenes_' . now()->format('Ymd_His') . '.' . $request->format;

            return Excel::download($export, $filename);
        }

        return back()->with('error', 'Tipo de reporte inválido.');
    }
}
