<?php

namespace App\Exports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PatientsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Patient::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'DNI',
            'RUC',
            'Teléfono',
            'Email',
            'Notas Dentales',
            'Observaciones',
            'Fecha de Registro',
        ];
    }

    public function map($patient): array
    {
        return [
            $patient->id,
            $patient->name,
            $patient->dni,
            $patient->ruc,
            $patient->phone,
            $patient->email,
            $patient->dental_notes,
            $patient->observations,
            $patient->created_at ? $patient->created_at->format('Y-m-d H:i') : '',
        ];
    }
}
