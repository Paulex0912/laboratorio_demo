<?php

namespace App\Console\Commands;

use App\Models\CashClosure;
use App\Models\CashMovement;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CloseCashCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'caja:cerrar {--date= : Fecha específica a cerrar (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cierra la caja del día, totalizando ingresos y egresos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateStr = $this->option('date') ?: date('Y-m-d');
        $date = Carbon::parse($dateStr)->format('Y-m-d');

        $this->info("Iniciando cierre de caja para el día: {$date}");

        $movements = CashMovement::where('date', $date)->get();

        $totalIncome = $movements->where('type', 'ingreso')->sum('amount');
        $totalExpense = $movements->where('type', 'egreso')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        CashClosure::updateOrCreate(
        ['date' => $date],
        [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $balance,
        ]
        );

        $this->info("Cierre completado. Ingresos: S/{$totalIncome} | Egresos: S/{$totalExpense} | Saldo: S/{$balance}");
    }
}
