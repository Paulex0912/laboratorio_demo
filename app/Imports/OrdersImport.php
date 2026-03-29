<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use App\Models\WorkOrder;
use App\Models\WorkType;
use Carbon\Carbon;

class OrdersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Skip header row
        $rows->shift();

        foreach ($rows as $row) {
            $rowArray = $row->toArray();
            // expected columns: paciente_dni, tecnico_dni, fecha_entrega, tipo_trabajo, material, color, precio
            $patientDni = $rowArray[0] ?? null;
            $technicianDni = $rowArray[1] ?? null;
            $dueDateRaw = $rowArray[2] ?? null;
            $workTypeName = $rowArray[3] ?? null;
            $material = $rowArray[4] ?? null;
            $color = $rowArray[5] ?? null;
            $price = $rowArray[6] ?? null;

            if (!$patientDni || !$workTypeName || !$price) {
                continue;
            }

            $patient = \App\Models\Patient::where('dni', $patientDni)->first();
            if (!$patient) {
                continue; // Required patient not found
            }

            $technician = null;
            if ($technicianDni) {
                $technician = \App\Models\Employee::where('document_number', $technicianDni)->first();
            }

            $dueDate = now();
            if ($dueDateRaw) {
                try {
                    // Try to parse standard formats or Excel numeric date
                    if (is_numeric($dueDateRaw)) {
                        $dueDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dueDateRaw);
                    } else {
                        $dueDate = Carbon::parse($dueDateRaw);
                    }
                } catch (\Exception $e) {
                    $dueDate = now()->addDays(3);
                }
            } else {
                $dueDate = now()->addDays(3);
            }

            $order = WorkOrder::create([
                'patient_id' => $patient->id,
                'technician_id' => $technician ? $technician->id : null,
                'due_date' => $dueDate,
                'amount' => $price,
                'status' => 'pendiente',
            ]);

            $workType = WorkType::where('name', $workTypeName)->first();

            $order->items()->create([
                'work_type_id' => $workType ? $workType->id : null,
                'type_name' => $workTypeName,
                'material' => $material,
                'color' => $color,
                'price' => $price,
            ]);
        }
    }
}
