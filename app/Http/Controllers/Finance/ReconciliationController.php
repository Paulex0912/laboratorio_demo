<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankMovement;
use App\Models\Invoice;
// use App\Models\Purchase; // We will use this in phase 3 when Purchase module is built
// use App\Models\ExpenseReport; // We will use this in phase 3 if we add expenses

class ReconciliationController extends Controller
{
    public function index(Request $request)
    {
        // 1. Unreconciled Bank Movements (Ingresos y Egresos huerfanos)
        $unreconciledMovementsQuery = BankMovement::where('reconciled', false)->with('account')->orderBy('date', 'desc');
        
        // 2. Pending Invoices (Cuentas por cobrar no saldadas)
        // We look for invoices that aren't fully paid or cancelled
        $pendingInvoicesQuery = Invoice::whereNotIn('status', ['pagada', 'anulada'])
            ->with(['patient', 'accountReceivable'])
            ->orderBy('issue_date', 'asc');

        if ($request->filled('tab')) {
            $currentTab = $request->tab;
        } else {
            $currentTab = 'movements'; // Default viewing tab
        }

        $unreconciledMovements = $unreconciledMovementsQuery->paginate(20, ['*'], 'movements_page');
        $pendingInvoices = $pendingInvoicesQuery->paginate(20, ['*'], 'invoices_page');

        // Optional: Stats
        $stats = [
            'unreconciled_count' => BankMovement::where('reconciled', false)->count(),
            'pending_invoices_count' => Invoice::whereNotIn('status', ['pagada', 'anulada'])->count(),
        ];

        return view('finance.reconciliation.index', compact('unreconciledMovements', 'pendingInvoices', 'currentTab', 'stats'));
    }
}
