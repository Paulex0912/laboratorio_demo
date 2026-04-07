<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Trabajos2025Seeder extends Seeder
{
    public function run()
    {
        // Ruta al archivo CSV que subiste
        $file = base_path('database/data/reporte_orden_trabajo_050326.xlsx - tbl_orden_trabajo.csv');
        
        if (!file_exists($file)) return;

        $csvData = file($file);

        foreach ($csvData as $index => $row) {
            // Saltamos las primeras 4 filas (cabeceras vacías)
            if ($index < 4) continue; 

            $data = str_getcsv($row);

            // Verificamos que sea una fila con datos (ID en la columna index 2)
            if (isset($data[2]) && is_numeric($data[2])) {
                DB::table('orders')->insert([
                    'id'            => $data[2],
                    'created_at'    => $data[3], // F. PEDIDO
                    'delivery_date' => $data[4], // F. ENTREGA
                    'patient_name'  => $data[6] ?: 'S/N', // PACIENTE
                    'total_amount'  => (float)$data[8], // TOTAL
                    'description'   => $data[11], // TRABAJO
                    'category'      => $data[12], // CATEGORIA
                    'doctor_name'   => $data[13], // DOCTOR
                    'clinic_name'   => $data[15], // CLINICA
                    'status'        => 'Entregado',
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}
