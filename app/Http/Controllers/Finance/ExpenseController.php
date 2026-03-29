<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseReport;
use App\Models\CashCategory;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Admin') || $user->hasRole('Tesorero')) {
            // Admin y Tesorero ven todo
            $reports = ExpenseReport::with(['user', 'approver'])->latest()->paginate(15);
        }
        else {
            // Empleado normal ve los suyos
            $reports = ExpenseReport::where('user_id', $user->id)->with(['approver'])->latest()->paginate(15);
        }

        return view('finance.expenses.index', compact('reports'));
    }

    public function create()
    {
        $categories = CashCategory::where('type', 'out')->orderBy('name')->get();
        return view('finance.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'lines' => 'required|array|min:1',
            'lines.*.category_id' => 'required|exists:cash_categories,id',
            'lines.*.description' => 'required|string|max:255',
            'lines.*.amount' => 'required|numeric|min:0.01',
            'lines.*.receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'action' => 'required|in:draft,submit',
        ]);

        $report = ExpenseReport::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'status' => $validated['action'] === 'submit' ? 'enviada' : 'borrador',
            'total' => 0 // Se calculará abajo
        ]);

        $totalAmount = 0;

        foreach ($validated['lines'] as $index => $lineData) {
            $path = null;
            if ($request->hasFile("lines.{$index}.receipt")) {
                $path = $request->file("lines.{$index}.receipt")->store('expense_receipts', 'public');
            }

            $report->lines()->create([
                'category_id' => $lineData['category_id'],
                'amount' => $lineData['amount'],
                'description' => $lineData['description'],
                'receipt_path' => $path,
            ]);

            $totalAmount += $lineData['amount'];
        }

        $report->update(['total' => $totalAmount]);

        $msg = $validated['action'] === 'submit'
            ? 'Rendición enviada a revisión exitosamente.'
            : 'Borrador guardado exitosamente.';

        return redirect()->route('expenses.index')->with('success', $msg);
    }

    public function show(ExpenseReport $expense)
    {
        // $expense ya inyectado por Route Model Binding
        $expense->load(['user', 'approver', 'lines.category']);

        // Empleado normal solo puede ver sus propias rendiciones
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('Tesorero')) {
            if ($expense->user_id !== Auth::id()) {
                abort(403, 'No tienes permiso para ver esta rendición de gastos.');
            }
        }

        return view('finance.expenses.show', ['report' => $expense]);
    }

    public function approve(Request $request, ExpenseReport $expense)
    {
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('Tesorero')) {
            abort(403, 'No tienes permiso para aprobar rendiciones.');
        }

        if ($expense->status !== 'enviada') {
            return back()->withErrors(['status' => 'Solo se pueden aprobar rendiciones enviadas a revisión.']);
        }

        $expense->update([
            'status' => 'aprobada',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $expense->user->notify(new \App\Notifications\ExpenseApprovedNotification($expense));

        return back()->with('success', 'La rendición ha sido APROBADA con éxito.');
    }

    public function reject(Request $request, ExpenseReport $expense)
    {
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('Tesorero')) {
            abort(403, 'No tienes permiso para rechazar rendiciones.');
        }

        if ($expense->status !== 'enviada') {
            return back()->withErrors(['status' => 'Solo se pueden rechazar rendiciones enviadas a revisión.']);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $expense->update([
            'status' => 'rechazada',
        ]);

        $expense->user->notify(new \App\Notifications\ExpenseRejectedNotification($expense, $validated['rejection_reason']));

        return back()->with('success', 'La rendición ha sido RECHAZADA.');
    }

    public function liquidate(Request $request, ExpenseReport $expense)
    {
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('Tesorero')) {
            abort(403, 'No tienes permiso para liquidar rendiciones.');
        }

        if ($expense->status !== 'aprobada') {
            return back()->withErrors(['status' => 'Solo se pueden liquidar rendiciones previamente aprobadas.']);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:caja,banco'
        ]);

        if ($validated['payment_method'] === 'caja') {
            // Impactar Caja General (Reutilizamos la categoría 1 de egresos o creamos una generica si prefieres)
            // Para simplificar, asumiremos que existe una categoría Out o tomamos la primera de tipo Out
            $category = CashCategory::where('type', 'out')->first();

            \App\Models\CashMovement::create([
                'user_id' => Auth::id(),
                'category_id' => $category->id ?? null,
                'type' => 'out',
                'amount' => $expense->total,
                'description' => 'Liq. Rendición N° ' . $expense->id . ' - ' . $expense->title,
            ]);
        }
        else {
            // Impactar un Banco (Tomamos la primera cuenta bancaria activa por defecto para el ejemplo,
            // en un sistema real se pediría el ID del banco en el formulario)
            $bankAccount = \App\Models\BankAccount::where('is_active', true)->first();

            if (!$bankAccount) {
                return back()->withErrors(['bank' => 'No hay cuentas bancarias registradas o activas para liquidar.']);
            }

            \App\Models\BankMovement::create([
                'bank_account_id' => $bankAccount->id,
                'user_id' => Auth::id(),
                'type' => 'out',
                'amount' => $expense->total,
                'description' => 'Liq. Rendición N° ' . $expense->id . ' - ' . $expense->title,
                'status' => 'conciliado', // Podemos dejarlo como conciliado directamente
            ]);
        }

        $expense->update([
            'status' => 'liquidada'
        ]);

        return back()->with('success', 'La rendición ha sido liquidada exitosamente en ' . strtoupper($validated['payment_method']) . '.');
    }
}
