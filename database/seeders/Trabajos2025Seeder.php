<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;
use Carbon\Carbon;

class Trabajos2025Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Asegúrate de que el nombre del archivo en la carpeta sea reporte_2025.csv
        $file = base_path('database/data/reporte_2025.csv');
        
        if (!file_exists($file)) return;

        $csvData = file($file);

        foreach ($csvData as $index => $row) {
            // Saltamos solo la primera fila (la de los títulos: ID, F. PEDIDO, etc.)
            if ($index == 0) continue; 

            $data = str_getcsv($row);

            // Ahora el ID está en el índice 0 (Columna A)
            if (isset($data[0]) && is_numeric($data[0])) {
                
                try {
                    // Buscar o crear paciente (Columna E del Excel original, ahora índice 4)
                    $nombrePaciente = !empty($data[4]) ? trim($data[4]) : 'PACIENTE - ORDEN ' . $data[0];
                    $patient = Patient::firstOrCreate(['name' => $nombrePaciente]);

                    // Insertar en work_orders
                    DB::table('work_orders')->updateOrInsert(
                        ['id' => $data[0]], 
                        [
                            'patient_id'    => $patient->id,
                            'status'        => 'entregado',
                            'type'          => $data[9] ?? 'Trabajo Dental', // TRABAJO
                            'material'      => $data[10] ?? null,            // CATEGORIA
                            'amount'        => isset($data[6]) ? (float)$data[6] : 0, // TOTAL
                            'due_date'      => $this->formatDate($data[2]),   // F. ENTREGA
                            'created_at'    => $this->formatDate($data[1]) ?: now(), // F. PEDIDO
                            'updated_at'    => now(),
                        ]
                    );
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
    }

    private function formatDate($date)
    {
        if (empty($date)) return null;
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}