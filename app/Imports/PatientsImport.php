<?php

namespace App\Imports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PatientsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Ignorar fila si no hay nombre
        if (!isset($row['nombre']) || empty(trim($row['nombre']))) {
            return null;
        }

        return new Patient([
            'name' => $row['nombre'],
            'dni' => $row['dni'] ?? null,
            'ruc' => $row['ruc'] ?? null,
            'phone' => $row['telefono'] ?? null,
            'email' => $row['email'] ?? null,
            'dental_notes' => $row['notas_dentales'] ?? null,
            'observations' => $row['observaciones'] ?? null,
            'created_by' => auth()->check() ? auth()->id() : 1,
        ]);
    }
}
