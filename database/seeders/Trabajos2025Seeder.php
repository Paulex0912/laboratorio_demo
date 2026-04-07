<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Trabajos2025Seeder extends Seeder
{
    public function run(): void
    {
        // Usamos el nombre simplificado
        $file = base_path('database/data/reporte_2025.csv');
        
        if (!file_exists($file)) {
            dump("Error: No encontré el archivo en " . $file);
            return;
        }

        $csvData = file($file);

        foreach ($csvData as $index => $row) {
            // Saltamos las 4 filas de encabezado del Excel
            if ($index < 4) continue; 

            $data = str_getcsv($row);

            // Verificamos que la columna ID (índice 2) tenga datos
            if (isset($data[2]) && !empty($data[2]) && is_numeric($data[2])) {
                DB::table('orders')->insert([
                    'id'            => $data[2],          // ID
                    'created_at'    => $data[3],          // F. PEDIDO
                    'delivery_date' => $data[4],          // F. ENTREGA
                    'patient_name'  => $data[6] ?: 'S/N', // PACIENTE
                    'total_amount'  => (float)$data[8],   // TOTAL
                    'description'   => $data[11],         // TRABAJO (Nombre)
                    'doctor_name'   => $data[13],         // DOCTOR
                    'clinic_name'   => $data[15],         // CLINICA
                    'status'        => 'Entregado',
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}