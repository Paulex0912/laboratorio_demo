<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BankAccount;

class TreasuryController extends Controller
{
    public function flow()
    {
        $accounts = BankAccount::all();
        $totalBanks = $accounts->sum('balance');
        $projectedBalance = $totalBanks;

        // Por ahora, enviaremos datos simulados para el gráfico hasta integrar con Cuentas por Cobrar/Pagar reales
        $months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'];
        $cashFlowData = [
            'ingresos' => [5000, 6000, 4500, 7000, 8000, 7500],
            'egresos' => [3000, 3500, 4000, 3200, 4100, 3800]
        ];

        return view('finance.treasury.flow', compact(
            'totalBanks',
            'projectedBalance',
            'months',
            'cashFlowData'
        ));
    }
}
