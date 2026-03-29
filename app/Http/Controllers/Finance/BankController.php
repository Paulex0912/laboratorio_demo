<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BankAccount;
use App\Models\BankMovement;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    // Listado de Cuentas y su saldo
    public function index()
    {
        $accounts = BankAccount::withCount(['movements' => function ($query) {
            $query->where('reconciled', false);
        }])->get();

        $totalBalance = $accounts->sum('balance');

        return view('finance.banks.index', compact('accounts', 'totalBalance'));
    }

    // Crear nueva cuenta
    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|unique:bank_accounts',
            'currency' => 'required|in:PEN,USD',
            'balance' => 'required|numeric',
        ]);

        BankAccount::create($validated);

        return back()->with('success', 'Cuenta bancaria registrada con éxito.');
    }

    // Ver movimientos de una cuenta
    public function show(BankAccount $bank)
    {
        $movements = $bank->movements()->latest('date')->latest('id')->paginate(15);
        return view('finance.banks.show', compact('bank', 'movements'));
    }

    // Registrar movimiento manual
    public function storeMovement(Request $request, BankAccount $bank)
    {
        $validated = $request->validate([
            'type' => 'required|in:ingreso,egreso',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:255',
        ]);

        $validated['bank_account_id'] = $bank->id;

        DB::transaction(function () use ($validated, $bank) {
            // Guardar movimiento
            BankMovement::create($validated);

            // Actualizar saldo de la cuenta
            if ($validated['type'] === 'ingreso') {
                $bank->increment('balance', $validated['amount']);
            }
            else {
                $bank->decrement('balance', $validated['amount']);
            }
        });

        return back()->with('success', 'Movimiento registrado correctamente.');
    }

    // Toggle Conciliado
    public function toggleReconciled(BankMovement $movement)
    {
        $movement->update([
            'reconciled' => !$movement->reconciled
        ]);

        $status = $movement->reconciled ? 'conciliado' : 'des-conciliado';
        return back()->with('success', "Movimiento marcado como {$status}.");
    }

    // Importación masiva
    public function import(Request $request, BankAccount $bank)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\BankMovementImport($bank->id), $request->file('file'));
            
            // Recalcular saldo total de la cuenta
            $bank->balance = $bank->movements()->where('type', 'ingreso')->sum('amount') 
                           - $bank->movements()->where('type', 'egreso')->sum('amount');
            $bank->save();

            return back()->with('success', 'Movimientos bancarios importados exitosamente. El saldo de la cuenta ha sido recalculado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al importar: ' . $e->getMessage());
        }
    }
}
