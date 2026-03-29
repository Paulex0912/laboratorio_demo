<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\CashMovement;
use App\Models\ExpenseReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function stats(Request $request)
    {
        // Rango de fechas por defecto: Mes actual
        $startDate = $request->input('start_date') ?Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfMonth();
        $endDate = $request->input('end_date') ?Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfMonth();

        // KPIs Basics (Filtrados por Rango)
        $ventasMes = Invoice::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'anulada')
            ->sum('total');

        $facturasVencidas = Invoice::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'pagada')
            ->where('status', '!=', 'anulada')
            ->whereDate('due_date', '<', today())
            ->sum('total');

        // Only count count of outstanding invoices
        $facturasPendientesCount = Invoice::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'pagada')
            ->where('status', '!=', 'anulada')
            ->count();

        $ordenesEnProceso = WorkOrder::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['pendiente', 'en_proceso'])->count();
        $ordenesTerminadas = WorkOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'terminado')->count();
        $ordenesEntregadas = WorkOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'entregado')->count();

        // Cajas
        $cajaIngresos = CashMovement::whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'ingreso')->sum('amount');
        $cajaEgresos = CashMovement::whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'egreso')->sum('amount');
        $saldoCaja = $cajaIngresos - $cajaEgresos;

        // Chart: Ventas ultimos 7 dias (Mantiene su flujo natural, ignorando filtro general para este chart específico, 
        // o adaptandose a los ultimos dias del end_date). Lo atamos al $endDate para ser consistentes con el filtro.
        $labelDias = collect();
        $dataDias = collect();
        for ($i = 6; $i >= 0; $i--) {
            // Usando endDate como "hoy" a fines del gráfico
            $fecha = (clone $endDate)->subDays($i);
            $labelDias->push($fecha->format('d/m'));

            $suma = Invoice::whereDate('created_at', $fecha)
                ->where('status', '!=', 'anulada')
                ->sum('total');
            $dataDias->push($suma);
        }

        // Nuevo: Chart Top 10 Clientes del Rango (Basado en WorkOrders o Invoices Facturadas)
        $topClientes = Invoice::join('patients', 'invoices.patient_id', '=', 'patients.id')
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->where('invoices.status', '!=', 'anulada')
            ->select('patients.name', DB::raw('SUM(invoices.total) as total_facturado'))
            ->groupBy('patients.id', 'patients.name')
            ->orderByDesc('total_facturado')
            ->limit(10)
            ->get();

        $topClientesLabels = $topClientes->map(function ($c) {
            return trim($c->name);
        });
        $topClientesData = $topClientes->pluck('total_facturado');

        // Nuevo: Chart Top Productos / Tipos de Trabajo
        $topProductos = WorkOrderItem::whereHas('workOrder', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->select('type_name', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('type_name')
            ->orderByDesc('cantidad')
            ->limit(5)
            ->get();

        $topProductosLabels = $topProductos->pluck('type_name');
        $topProductosData = $topProductos->pluck('cantidad');

        return response()->json([
            'kpis' => [
                'ventas_mes' => number_format($ventasMes, 2),
                'saldo_caja' => number_format($saldoCaja, 2),
                'facturas_vencidas_monto' => number_format($facturasVencidas, 2),
                'facturas_pendientes' => $facturasPendientesCount,
                'ordenes_activas' => $ordenesEnProceso,
            ],
            'charts' => [
                'ventas_dias' => [
                    'labels' => $labelDias,
                    'data' => $dataDias
                ],
                'ordenes_dona' => [
                    'labels' => ['En Proceso', 'Terminadas', 'Entregadas'],
                    'data' => [$ordenesEnProceso, $ordenesTerminadas, $ordenesEntregadas]
                ],
                'top_clientes' => [
                    'labels' => $topClientesLabels,
                    'data' => $topClientesData
                ],
                'top_productos' => [
                    'labels' => $topProductosLabels,
                    'data' => $topProductosData
                ]
            ]
        ]);
    }
}
