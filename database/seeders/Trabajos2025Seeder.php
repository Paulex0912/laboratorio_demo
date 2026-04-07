<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;

class Trabajos2025Seeder extends Seeder
{
    public function run(): void
    {
        $file = base_path('database/data/reporte_2025.csv');
        
        if (!file_exists($file)) return;

        $csvData = file($file);

        foreach ($csvData as $index => $row) {
            if ($index < 4) continue; 

            $data = str_getcsv($row);

            // Verificamos que haya un ID de orden en la columna índice 2
            if (isset($data[2]) && is_numeric($data[2])) {
                
                // 1. Buscamos o creamos al paciente primero (porque es una llave foránea obligatoria)
                $patientName = $data[6] ?: 'PACIENTE GENERICO';
                $patient = Patient::firstOrCreate(['name' => $patientName]);

                // 2. Insertamos en work_orders usando los nombres exactos de tu migración
                DB::table('work_orders')->insert([
                    'id'            => $data[2],
                    'patient_id'    => $patient->id,
                    'status'        => 'entregado',
                    'type'          => $data[11] ?: 'Trabajo Dental', // Columna TRABAJO
                    'material'      => $data[12],                    // Columna CATEGORIA
                    'amount'        => (float)$data[8],              // Columna TOTAL
                    'due_date'      => $data[4],                     // Columna F. ENTREGA
                    'delivered_at'  => $data[4] . ' 12:00:00',
                    'created_at'    => $data[3],                     // Columna F. PEDIDO
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}