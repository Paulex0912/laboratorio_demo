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
        $file = base_path('database/data/reporte_2025.csv');
        
        if (!file_exists($file)) {
            dump("Error: No se encuentra el archivo en database/data/reporte_2025.csv");
            return;
        }

        $csvData = file($file);
        $count = 0;

        foreach ($csvData as $index => $row) {
            // Saltamos solo la primera fila (títulos)
            if ($index == 0) continue; 

            $data = str_getcsv($row);

            // Ahora el ID es el índice 0 porque ya limpiaste el Excel
            if (isset($data[0]) && is_numeric($data[0])) {
                try {
                    // 1. Gestionar Paciente (Columna E/índice 4: PACIENTE)
                    $nombrePaciente = !empty($data[4]) ? trim($data[4]) : 'PACIENTE - ORDEN ' . $data[0];
                    $patient = Patient::firstOrCreate(['name' => $nombrePaciente]);

                    // 2. Insertar en work_orders
                    DB::table('work_orders')->updateOrInsert(
                        ['id' => $data[0]], 
                        [
                            'patient_id'    => $patient->id,
                            'status'        => 'entregado',
                            'type'          => $data[9] ?? 'Trabajo Dental', // Columna J: TRABAJO
                            'material'      => $data[10] ?? null,            // Columna K: CATEGORIA
                            'amount'        => isset($data[6]) ? (float)$data[6] : 0, // Columna G: TOTAL
                            'due_date'      => $this->parseDate($data[2]),   // Columna C: F. ENTREGA
                            'created_at'    => $this->parseDate($data[1]) ?: now(), // Columna B: F. PEDIDO
                            'updated_at'    => now(),
                        ]
                    );
                    $count++;
                } catch (\Exception $e) {
                    dump("Error en ID {$data[0]}: " . $e->getMessage());
                }
            }
        }
        dump("Finalizado. Se cargaron $count registros.");
    }

    private function parseDate($date) {
        if (empty($date)) return null;
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}