<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CashMovement;
use App\Models\CashCategory;
use Illuminate\Support\Facades\Storage;

class CashController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        $movements = CashMovement::with(['category', 'cashier'])
            ->where('date', $date)
            ->latest()
            ->get();

        $totalIncome = $movements->where('type', 'ingreso')->sum('amount');
        $totalExpense = $movements->where('type', 'egreso')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $categories = CashCategory::orderBy('name')->get();

        return view('finance.cash.index', compact('movements', 'totalIncome', 'totalExpense', 'balance', 'date', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:ingreso,egreso',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:cash_categories,id',
            'ref_doc' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'date' => 'required|date',
            'receipt' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        $validated['cashier_id'] = auth()->id();

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt_path'] = $path;
        }

        CashMovement::create($validated);

        return back()->with('success', 'Movimiento de caja registrado correctamente.');
    }
}
