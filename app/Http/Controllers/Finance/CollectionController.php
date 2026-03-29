<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\AccountReceivable;
use App\Models\CustomerPayment;
use App\Models\CashMovement;
use App\Models\BankMovement;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CollectionController extends Controller
{
    /**
     * Display a listing of patients with outstanding balances.
     */
    public function index(Request $request)
    {
        // Obtener pacientes que tienen cuentas por cobrar con balance mayor a 0
        $query = Patient::whereHas('accountReceivables', function ($q) use ($request) {
            $q->where('balance', '>', 0);
            if ($request->filled('date_from')) {
                $q->whereDate('due_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $q->whereDate('due_date', '<=', $request->date_to);
            }
        })->with(['accountReceivables' => function ($q) use ($request) {
            $q->where('balance', '>', 0);
            if ($request->filled('date_from')) {
                $q->whereDate('due_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $q->whereDate('due_date', '<=', $request->date_to);
            }
        }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        $patients = $query->paginate(15);

        // Agregamos un atributo calculado sumando la deuda total
        $patients->getCollection()->transform(function ($patient) {
            $patient->total_debt = $patient->accountReceivables->sum('balance');
            return $patient;
        });

        return view('finance.collections.index', compact('patients'));
    }

    /**
     * Display the specific outstanding receivables for a patient to apply payments.
     */
    public function show(Patient $patient)
    {
        // Cargar cuentas pendientes activas ordenadas por la más antigua primero (FIFO)
        $receivables = $patient->accountReceivables()
            ->where('balance', '>', 0)
            ->orderBy('due_date', 'asc')
            ->get();

        $totalDebt = $receivables->sum('balance');
        $banks = BankAccount::where('status', 'activo')->get(); // Para transferencias

        // Cargar historial de pagos recientes del paciente
        $paymentHistory = CustomerPayment::where('patient_id', $patient->id)
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        return view('finance.collections.show', compact('patient', 'receivables', 'totalDebt', 'banks', 'paymentHistory'));
    }

    /**
     * Store a payment and apply it (FIFO) to outstanding balances.
     */
    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:Efectivo,Transferencia,Tarjeta,Cheque',
            'bank_account_id' => 'required_if:payment_method,Transferencia|nullable|exists:bank_accounts,id',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $amountToApply = $request->amount_paid;

        // Obtener cuentas por cobrar con deuda ordenadas de forma ascendente por fecha (FIFO)
        $receivables = $patient->accountReceivables()
            ->where('balance', '>', 0)
            ->orderBy('due_date', 'asc')
            ->get();

        $totalDebt = $receivables->sum('balance');

        if ($amountToApply > $totalDebt) {
            return back()->with('error', 'El monto a abonar (' . number_format($amountToApply, 2) . ') no puede superar la deuda total (' . number_format($totalDebt, 2) . ').');
        }

        DB::beginTransaction();

        try {
            // Generar identificador de recibo general de ingreso de caja
            $receiptNumber = 'RC-' . date('Ymd') . '-' . rand(1000, 9999);
            $appliedAmountTotal = 0;

            foreach ($receivables as $receivable) {
                if ($amountToApply <= 0)
                    break;

                // Determinar cuánto podemos aplicar a esta cuenta específica
                $paymentForThisReceivable = min($amountToApply, $receivable->balance);

                // Reducir balance
                $receivable->balance -= $paymentForThisReceivable;
                if ($receivable->balance == 0) {
                    $receivable->status = 'pagado';
                }
                else {
                    $receivable->status = 'parcial';
                }
                $receivable->save();

                // Registrar Historial de Pago
                $payment = CustomerPayment::create([
                    'account_receivable_id' => $receivable->id,
                    'patient_id' => $patient->id,
                    'user_id' => auth()->id(),
                    'amount' => $paymentForThisReceivable,
                    'payment_date' => Carbon::now(),
                    'payment_method' => $request->payment_method,
                    'reference_number' => $request->reference_number,
                    'receipt_number' => $receiptNumber . '-' . $receivable->id,
                    'notes' => 'Abono Parcial Vía Tesorería'
                ]);

                // Registrar Ingreso en Caja o Banco
                if ($request->payment_method === 'Efectivo') {
                    CashMovement::create([
                        'user_id' => auth()->id(),
                        'type' => 'ingreso',
                        'amount' => $paymentForThisReceivable,
                        'concept' => 'Cobranza de Deuda - Paciente: ' . $patient->name,
                        'voucher_type' => 'Recibo',
                        'voucher_number' => $payment->receipt_number,
                        'movement_date' => Carbon::now(),
                    ]);
                }
                else {
                    // Si no es efectivo, va a bancos, si no se seleccionó banco en cheque/tarjeta usaremos null
                    if ($request->bank_account_id) {
                        BankMovement::create([
                            'bank_account_id' => $request->bank_account_id,
                            'type' => 'ingreso',
                            'amount' => $paymentForThisReceivable,
                            'concept' => 'Cobranza de Deuda (' . $request->payment_method . ') - Paciente: ' . $patient->name . ' Ref: ' . $request->reference_number,
                            'reference_number' => $request->reference_number,
                            'movement_date' => Carbon::now(),
                        ]);
                    }
                }

                $amountToApply -= $paymentForThisReceivable;
                $appliedAmountTotal += $paymentForThisReceivable;
            }

            DB::commit();

            return redirect()->route('collections.show', $patient)->with('success', 'Abono de S/ ' . number_format($appliedAmountTotal, 2) . ' aplicado correctamente a las deudas pendientes.');

        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al procesar la cobranza: ' . $e->getMessage());
        }
    }
}
